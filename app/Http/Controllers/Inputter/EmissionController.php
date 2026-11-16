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
            ->latest('tanggal_aktivitas')->latest('id')
            ->paginate(15)->withQueryString();

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
            'lands'    => Land::where('status_operasional', 'aktif')->orderBy('nama_lahan')->get(),
            'factors'  => EmissionFactor::where('kategori', $cfg['faktor_kategori'])->orderBy('nama_faktor')->get(),
        ]);
    }

    public function store(Request $request, string $kategori)
    {
        $cfg = $this->cfg($kategori);

        $rules = [
            'land_id' => ['required', Rule::exists('lands', 'id')->where('status_operasional', 'aktif')],
            'emission_factor_id' => ['required', Rule::exists('emission_factors', 'id')
                ->where('kategori', $cfg['faktor_kategori'])],
            'tanggal_aktivitas' => ['required', 'date', 'before_or_equal:today'],
            'bukti_dokumen' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];

        if (! isset($cfg['jumlah_dari'])) {
            $rules['jumlah_penggunaan'] = ['required', 'numeric', 'gt:0'];
        }

        foreach ($cfg['extra'] as $f) {
            $r = match ($f['type']) {
                'number' => ['numeric', 'gt:0'],
                'select' => [Rule::in($f['options'])],
                default  => ['string', 'max:255'],
            };
            $rules['detail.' . $f['name']] = array_merge([$f['required'] ? 'required' : 'nullable'], $r);
        }

        $data = $request->validate($rules, [
            'bukti_dokumen.mimes' => 'Bukti harus berformat PDF, JPG, atau PNG.',
            'bukti_dokumen.max'   => 'Ukuran bukti maksimal 2 MB.',
        ]);
        $detail = $data['detail'] ?? [];

        // jumlah_penggunaan: input langsung, atau hasil kali (mis. jarak x muatan = ton-km)
        $jumlah = isset($cfg['jumlah_dari'])
            ? array_reduce($cfg['jumlah_dari'], fn ($c, $k) => $c * (float) $detail[$k], 1)
            : (float) $data['jumlah_penggunaan'];

        $factor = EmissionFactor::findOrFail($data['emission_factor_id']);

        $path = $request->hasFile('bukti_dokumen')
            ? $request->file('bukti_dokumen')->store('bukti', 'public')
            : null;

        Emission::create([
            'land_id'            => $data['land_id'],
            'kategori_aktivitas' => $kategori,
            'emission_factor_id' => $factor->id,
            'jumlah_penggunaan'  => $jumlah,
            'total_emisi_kotor'  => round($jumlah * $factor->nilai_emisi, 4), // negatif untuk penyerapan
            'detail_tambahan'    => array_filter($detail, fn ($v) => $v !== null && $v !== ''),
            'bukti_dokumen'      => $path,
            'tanggal_aktivitas'  => $data['tanggal_aktivitas'],
        ]);

        return redirect()->route('inputter.emissions.index')
            ->with('status', "Data {$cfg['label']} berhasil disimpan.");
    }

    public function destroy(Emission $emission)
    {
        if ($emission->bukti_dokumen) {
            Storage::disk('public')->delete($emission->bukti_dokumen);
        }
        $emission->delete();

        return back()->with('status', 'Data dihapus.');
    }
}