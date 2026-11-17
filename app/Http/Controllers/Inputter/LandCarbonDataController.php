<?php

namespace App\Http\Controllers\Inputter;

use App\Http\Controllers\Controller;
use App\Models\Land;
use App\Models\LandCarbonData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LandCarbonDataController extends Controller
{
    private const TILLAGE_METHODS = [
        'no_till',
        'minimum_tillage',
        'conventional_tillage',
    ];

    public function index(Request $request)
    {
        $carbonData = LandCarbonData::with('land')
            ->when($request->land_id, fn ($query, $value) => $query->where('land_id', $value))
            ->latest('tanggal_aktivitas')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('inputter.land-carbon-data.index', [
            'carbonData' => $carbonData,
            'lands' => Land::orderBy('nama_lahan')->get(),
        ]);
    }

    public function create()
    {
        return view('inputter.land-carbon-data.create', [
            'lands' => Land::where('status_operasional', 'aktif')
                ->orderBy('nama_lahan')
                ->get(),
            'tillageMethods' => self::TILLAGE_METHODS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'land_id' => [
                'required',
                Rule::exists('lands', 'id')
                    ->where('status_operasional', 'aktif'),
            ],
            'tanggal_aktivitas' => ['required', 'date', 'before_or_equal:today'],
            'trees_planted' => ['required', 'integer', 'min:0'],
            'cover_crop_area' => ['required', 'numeric', 'min:0'],
            'tillage_method' => ['required', Rule::in(self::TILLAGE_METHODS)],
            'biochar_amount' => ['required', 'numeric', 'min:0'],
            'compost_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], $this->validationMessages());

        LandCarbonData::create($data);

        return redirect()
            ->route('inputter.land-carbon-data.index')
            ->with('status', 'Data penyerapan karbon alam berhasil disimpan.');
    }

    public function show(LandCarbonData $landCarbonData)
    {
        $landCarbonData->load('land');

        return view('inputter.land-carbon-data.show', [
            'item' => $landCarbonData,
        ]);
    }

    public function edit(LandCarbonData $landCarbonData)
    {
        $lands = Land::where('status_operasional', 'aktif')
            ->orWhere('id', $landCarbonData->land_id)
            ->orderBy('nama_lahan')
            ->get();

        return view('inputter.land-carbon-data.edit', [
            'item' => $landCarbonData,
            'lands' => $lands,
            'tillageMethods' => self::TILLAGE_METHODS,
        ]);
    }

    public function update(Request $request, LandCarbonData $landCarbonData)
    {
        $data = $request->validate([
            'land_id' => [
                'required',
                'integer',
                Rule::exists('lands', 'id'),
                function ($attribute, $value, $fail) use ($landCarbonData) {
                    $land = Land::find($value);

                    if (
                        !$land ||
                        ($land->status_operasional !== 'aktif' &&
                            (int) $value !== (int) $landCarbonData->land_id)
                    ) {
                        $fail('Lahan yang dipilih tidak tersedia atau tidak aktif.');
                    }
                },
            ],
            'tanggal_aktivitas' => ['required', 'date', 'before_or_equal:today'],
            'trees_planted' => ['required', 'integer', 'min:0'],
            'cover_crop_area' => ['required', 'numeric', 'min:0'],
            'tillage_method' => ['required', Rule::in(self::TILLAGE_METHODS)],
            'biochar_amount' => ['required', 'numeric', 'min:0'],
            'compost_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], $this->validationMessages());

        $landCarbonData->update($data);

        return redirect()
            ->route('inputter.land-carbon-data.index')
            ->with('status', 'Data penyerapan karbon alam berhasil diperbarui.');
    }

    public function destroy(LandCarbonData $landCarbonData)
    {
        $landCarbonData->delete();

        return redirect()
            ->route('inputter.land-carbon-data.index')
            ->with('status', 'Data penyerapan karbon alam berhasil dihapus.');
    }

    private function validationMessages(): array
    {
        return [
            'land_id.required' => 'Lahan wajib dipilih.',
            'land_id.exists' => 'Lahan yang dipilih tidak tersedia atau tidak aktif.',
            'tanggal_aktivitas.required' => 'Tanggal aktivitas wajib diisi.',
            'tanggal_aktivitas.before_or_equal' => 'Tanggal aktivitas tidak boleh melebihi hari ini.',
            'trees_planted.min' => 'Jumlah pohon tidak boleh negatif.',
            'cover_crop_area.min' => 'Luas tanaman penutup tanah tidak boleh negatif.',
            'tillage_method.in' => 'Metode pengolahan tanah tidak valid.',
            'biochar_amount.min' => 'Jumlah biochar tidak boleh negatif.',
            'compost_amount.min' => 'Jumlah kompos tidak boleh negatif.',
        ];
    }
}
