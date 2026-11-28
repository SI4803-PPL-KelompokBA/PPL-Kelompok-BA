<?php

namespace App\Http\Controllers\Inputter;

use App\Http\Controllers\Controller;
use App\Models\Emission;
use App\Models\EmissionFactor;
use App\Models\Land;
use App\Models\LandCarbonData;
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

    private function validationRules(array $cfg): array
    {
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
            'bukti_dokumen' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
            'detail' => ['nullable', 'array'],
        ];

        if (!isset($cfg['jumlah_dari'])) {
            $rules['jumlah_penggunaan'] = [
                'required',
                'numeric',
                'gt:0',
            ];
        }

        foreach ($cfg['extra'] as $field) {
            $fieldRules = match ($field['type']) {
                'number' => ['numeric', 'gt:0'],
                'select' => [Rule::in($field['options'])],
                default => ['string', 'max:255'],
            };

            $rules['detail.' . $field['name']] = array_merge(
                [$field['required'] ? 'required' : 'nullable'],
                $fieldRules
            );
        }

        return $rules;
    }

    private function emissionAttributes(
        array $data,
        string $kategori,
        array $cfg
    ): array {
        $detail = $data['detail'] ?? [];

        $jumlah = isset($cfg['jumlah_dari'])
            ? array_reduce(
                $cfg['jumlah_dari'],
                fn ($total, $key) => $total * (float) $detail[$key],
                1
            )
            : (float) $data['jumlah_penggunaan'];

        $factor = EmissionFactor::where(
            'kategori',
            $cfg['faktor_kategori']
        )->findOrFail($data['emission_factor_id']);

        return [
            'land_id' => $data['land_id'],
            'kategori_aktivitas' => $kategori,
            'emission_factor_id' => $factor->id,
            'jumlah_penggunaan' => round($jumlah, 4),
            'total_emisi_kotor' => round(
                $jumlah * $factor->nilai_emisi,
                4
            ),
            'detail_tambahan' => array_filter(
                $detail,
                fn ($value) => $value !== null && $value !== ''
            ),
            'tanggal_aktivitas' => $data['tanggal_aktivitas'],
        ];
    }

    public function index(Request $request)
    {
        $emissions = Emission::with(['land', 'factor'])
            ->when(
                $request->kategori,
                fn ($query, $value) =>
                    $query->where('kategori_aktivitas', $value)
            )
            ->when(
                $request->land_id,
                fn ($query, $value) =>
                    $query->where('land_id', $value)
            )
            ->latest('tanggal_aktivitas')
            ->latest('id')
            ->paginate(15, ['*'], 'emission_page')
            ->withQueryString();

        $carbonData = LandCarbonData::with('land')
            ->when(
                $request->kategori && $request->kategori !== 'penyerapan',
                fn ($query) => $query->where('id', 0)
            )
            ->when(
                $request->land_id,
                fn ($query, $value) =>
                    $query->where('land_id', $value)
            )
            ->latest('tanggal_aktivitas')
            ->latest('id')
            ->paginate(15, ['*'], 'carbon_page')
            ->withQueryString();

        return view('inputter.emissions.index', [
            'emissions' => $emissions,
            'carbonData' => $carbonData,
            'lands' => Land::orderBy('nama_lahan')->get(),
            'kategoris' => config('cbam.kategori'),
        ]);
    }

    public function create(string $kategori)
    {
        $cfg = $this->cfg($kategori);

        return view('inputter.emissions.create', [
            'kategori' => $kategori,
            'cfg' => $cfg,
            'lands' => Land::where(
                'status_operasional',
                'aktif'
            )->orderBy('nama_lahan')->get(),
            'factors' => EmissionFactor::where(
                'kategori',
                $cfg['faktor_kategori']
            )->orderBy('nama_faktor')->get(),
            'emission' => null,
            'isEdit' => false,
        ]);
    }

    public function store(Request $request, string $kategori)
    {
        $cfg = $this->cfg($kategori);

        $data = $request->validate(
            $this->validationRules($cfg),
            [
                'bukti_dokumen.mimes' =>
                    'Bukti harus berformat PDF, JPG, atau PNG.',
                'bukti_dokumen.max' =>
                    'Ukuran bukti maksimal 2 MB.',
                'detail.tujuan.required' =>
                    'Fasilitas pemrosesan tujuan wajib diisi.',
            ]
        );

        $attributes = $this->emissionAttributes(
            $data,
            $kategori,
            $cfg
        );

        if ($request->hasFile('bukti_dokumen')) {
            $attributes['bukti_dokumen'] = $request
                ->file('bukti_dokumen')
                ->store('bukti', 'public');
        }

        Emission::create($attributes);

        return redirect()
            ->route('inputter.emissions.index')
            ->with('status', "Data {$cfg['label']} berhasil disimpan.");
    }

    public function edit(Emission $emission)
    {
        $kategori = $emission->kategori_aktivitas;
        $cfg = $this->cfg($kategori);

        return view('inputter.emissions.create', [
            'kategori' => $kategori,
            'cfg' => $cfg,
            'lands' => Land::where(
                'status_operasional',
                'aktif'
            )->orderBy('nama_lahan')->get(),
            'factors' => EmissionFactor::where(
                'kategori',
                $cfg['faktor_kategori']
            )->orderBy('nama_faktor')->get(),
            'emission' => $emission->load(['land', 'factor']),
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Emission $emission)
    {
        $kategori = $emission->kategori_aktivitas;
        $cfg = $this->cfg($kategori);

        $data = $request->validate(
            $this->validationRules($cfg),
            [
                'bukti_dokumen.mimes' =>
                    'Bukti harus berformat PDF, JPG, atau PNG.',
                'bukti_dokumen.max' =>
                    'Ukuran bukti maksimal 2 MB.',
                'detail.tujuan.required' =>
                    'Fasilitas pemrosesan tujuan wajib diisi.',
            ]
        );

        $attributes = $this->emissionAttributes(
            $data,
            $kategori,
            $cfg
        );

        $oldDocument = $emission->bukti_dokumen;

        if ($request->hasFile('bukti_dokumen')) {
            $attributes['bukti_dokumen'] = $request
                ->file('bukti_dokumen')
                ->store('bukti', 'public');
        }

        $emission->update($attributes);

        if (
            $request->hasFile('bukti_dokumen') &&
            $oldDocument
        ) {
            Storage::disk('public')->delete($oldDocument);
        }

        return redirect()
            ->route('inputter.emissions.index')
            ->with('status', "Data {$cfg['label']} berhasil diperbarui.");
    }

    public function destroy(Emission $emission)
    {
        if ($emission->bukti_dokumen) {
            Storage::disk('public')->delete(
                $emission->bukti_dokumen
            );
        }

        $emission->delete();

        return back()->with('status', 'Data berhasil dihapus.');
    }
}
