<?php

namespace App\Http\Controllers\Inputter;

use App\Http\Controllers\Controller;
use App\Models\Emission;
use App\Models\EmissionFactor;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmissionController extends Controller
{
    private function cfg(string $kategori): array
    {
        $cfg = config("cbam.kategori.$kategori");

        abort_if(is_null($cfg), 404);

        return $cfg;
    }

    public function index(Request $request)
    {
        $emissions = Emission::with(['land', 'factor'])
            ->when($request->kategori, fn ($q, $v) => $q->where('kategori_aktivitas', $v))
            ->when($request->land_id, fn ($q, $v) => $q->where('land_id', $v))
            ->latest('tanggal_aktivitas')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('inputter.emissions.index', [
            'emissions' => $emissions,
            'lands'     => Land::orderBy('nama_lahan')->get(),
            'kategoris' => config('cbam.kategori'),
        ]);
    }

    public function create(string $kategori)
    {
        $cfg = $this->cfg($kategori);

        return view('inputter.emissions.create', [
            'kategori' => $kategori,
            'cfg'      => $cfg,
            'lands'    => Land::where('status_operasional', 'aktif')
                ->orderBy('nama_lahan')
                ->get(),
            'factors'  => EmissionFactor::where('kategori', $cfg['faktor_kategori'])
                ->orderBy('nama_faktor')
                ->get(),
        ]);
    }

    public function store(Request $request, string $kategori)
    {
        $cfg = $this->cfg($kategori);

        // Validasi data emisi dan bukti dokumen
        $rules = [
            'land_id' => [
                'required',
                Rule::exists('lands', 'id')
                    ->where('status_operasional', 'aktif'),
            ],

            'emission_factor_id' => [
                'required',
                Rule::exists('emission_factors', 'id')
                    ->where('kategori', $cfg['faktor_kategori']),
            ],

            'tanggal_aktivitas' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            // Validasi bukti dokumen PBI-06
            'bukti_dokumen' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ];

        if (! isset($cfg['jumlah_dari'])) {
            $rules['jumlah_penggunaan'] = [
                'required',
                'numeric',
                'gt:0',
            ];
        }

        // Validasi field tambahan berdasarkan konfigurasi kategori
        foreach ($cfg['extra'] as $f) {
            $r = match ($f['type']) {
                'number' => ['numeric', 'gt:0'],
                'select' => [Rule::in($f['options'])],
                default  => ['string', 'max:255'],
            };

            $rules['detail.' . $f['name']] = array_merge(
                [$f['required'] ? 'required' : 'nullable'],
                $r
            );
        }

        $data = $request->validate($rules, [
            'bukti_dokumen.file'  => 'Dokumen bukti tidak valid.',
            'bukti_dokumen.mimes' => 'Bukti harus berformat PDF, JPG, atau PNG.',
            'bukti_dokumen.max'   => 'Ukuran bukti maksimal 2 MB.',
        ]);

        $detail = $data['detail'] ?? [];

        // Menghitung jumlah penggunaan
        // Input langsung atau hasil perkalian (misalnya jarak x muatan = ton-km)
        $jumlah = isset($cfg['jumlah_dari'])
            ? array_reduce(
                $cfg['jumlah_dari'],
                fn ($c, $k) => $c * (float) $detail[$k],
                1
            )
            : (float) $data['jumlah_penggunaan'];

        $factor = EmissionFactor::findOrFail($data['emission_factor_id']);

        // Upload bukti dokumen ke public storage
        $path = null;

        if ($request->hasFile('bukti_dokumen')) {
            $path = $request->file('bukti_dokumen')
                ->store('bukti', 'public');

            if (! $path) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'bukti_dokumen' => 'Gagal menyimpan dokumen bukti.',
                    ]);
            }
        }

        // Simpan data emisi
        Emission::create([
            'land_id'            => $data['land_id'],
            'kategori_aktivitas' => $kategori,
            'emission_factor_id' => $factor->id,
            'jumlah_penggunaan'  => $jumlah,
            'total_emisi_kotor'  => round(
                $jumlah * $factor->nilai_emisi,
                4
            ),
            'detail_tambahan'    => array_filter(
                $detail,
                fn ($v) => $v !== null && $v !== ''
            ),
            'bukti_dokumen'      => $path,
            'tanggal_aktivitas'  => $data['tanggal_aktivitas'],
        ]);

        return redirect()
            ->route('inputter.emissions.index')
            ->with('status', "Data {$cfg['label']} berhasil disimpan.");
    }

    /**
     * Upload atau mengganti bukti dokumen pada data emisi yang sudah ada.
     */
    public function updateBukti(Request $request, Emission $emission)
    {
        // Error bag khusus untuk setiap entri emisi
        $errorBag = 'bukti_' . $emission->id;

        // Validasi bukti dokumen
        $request->validateWithBag($errorBag, [
            'bukti_dokumen' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
        ], [
            'bukti_dokumen.required' => 'Silakan pilih dokumen bukti.',
            'bukti_dokumen.file'     => 'Dokumen bukti tidak valid.',
            'bukti_dokumen.mimes'    => 'Bukti harus berformat PDF, JPG, atau PNG.',
            'bukti_dokumen.max'      => 'Ukuran bukti maksimal 2 MB.',
        ]);

        // Simpan file baru ke public storage
        $pathBaru = $request->file('bukti_dokumen')
            ->store('bukti', 'public');

        if (! $pathBaru) {
            return back()->withErrors([
                'bukti_dokumen' => 'Gagal menyimpan dokumen bukti.',
            ], $errorBag);
        }

        // Simpan path bukti lama
        $pathLama = $emission->bukti_dokumen;

        // Perbarui path bukti di database
        try {
            $berhasil = $emission->update([
                'bukti_dokumen' => $pathBaru,
            ]);
        } catch (\Throwable $exception) {
            // Hapus file baru jika proses database gagal
            Storage::disk('public')->delete($pathBaru);

            throw $exception;
        }

        if (! $berhasil) {
            Storage::disk('public')->delete($pathBaru);

            return back()->withErrors([
                'bukti_dokumen' => 'Gagal memperbarui dokumen bukti.',
            ], $errorBag);
        }

        // Hapus file lama setelah path baru berhasil disimpan
        if ($pathLama && $pathLama !== $pathBaru) {
            Storage::disk('public')->delete($pathLama);
        }

        // Pesan berdasarkan kondisi bukti sebelumnya
        $pesan = $pathLama
            ? 'Bukti dokumen berhasil diganti.'
            : 'Bukti dokumen berhasil diunggah.';

        return back()->with('status', $pesan);
    }

    /**
     * Menghapus data emisi beserta dokumen buktinya.
     */
    public function destroy(Emission $emission)
    {
        // Hapus file bukti dari public storage
        if ($emission->bukti_dokumen) {
            Storage::disk('public')->delete($emission->bukti_dokumen);
        }

        // Hapus data emisi
        $emission->delete();

        return back()->with('status', 'Data dihapus.');
    }
}