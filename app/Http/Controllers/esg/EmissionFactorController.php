<?php

namespace App\Http\Controllers\Esg;

use App\Http\Controllers\Controller;
use App\Models\EmissionFactor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EmissionFactorController extends Controller
{
    /** Nama kategori penyerapan (sesuai faktor_kategori di config/cbam/penyerapan.php). */
    private const PENYERAPAN = 'Penyerapan Karbon';

    /** Kategori tambahan di luar config/cbam. */
    private const EXTRA_KATEGORI = ['BBM'];

    public function index(Request $request): View
    {
        $kategoriList = $this->kategoriList();

        $factors = EmissionFactor::query()
            ->when(
                $request->filled('kategori') && in_array($request->kategori, $kategoriList, true),
                fn ($q) => $q->where('kategori', $request->kategori)
            )
            ->orderBy('kategori')
            ->orderBy('nama_faktor')
            ->paginate(15)
            ->withQueryString();

        return view('esg.factors.index', [
            'factors'      => $factors,
            'kategoriList' => $kategoriList,
            'filter'       => $request->kategori,
        ]);
    }

    public function create(): View
    {
        return view('esg.factors.create', [
            'factor'       => new EmissionFactor(),
            'kategoriList' => $this->kategoriList(),
            'penyerapan'   => self::PENYERAPAN,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        (new EmissionFactor())->forceFill($data)->save();

        return redirect()->route('esg.emission-factors.index')
            ->with('success', 'Faktor emisi berhasil ditambahkan.');
    }

    public function edit(EmissionFactor $factor): View
    {
        return view('esg.factors.edit', [
            'factor'       => $factor,
            'kategoriList' => $this->kategoriList(),
            'penyerapan'   => self::PENYERAPAN,
        ]);
    }

    public function update(Request $request, EmissionFactor $factor): RedirectResponse
    {
        $data = $this->validated($request, $factor);

        $factor->forceFill($data)->save();

        return redirect()->route('esg.emission-factors.index')
            ->with('success', 'Faktor emisi berhasil diperbarui. Data emisi lama tidak berubah sampai dilakukan perhitungan ulang.');
    }

    public function destroy(EmissionFactor $factor): RedirectResponse
    {
        $dipakai = DB::table('emissions')->where('emission_factor_id', $factor->id)->exists();

        if ($dipakai) {
            return back()->withErrors([
                'hapus' => "Faktor \"{$factor->nama_faktor}\" tidak bisa dihapus karena sudah dipakai pada data emisi.",
            ]);
        }

        $factor->delete();

        return redirect()->route('esg.emission-factors.index')
            ->with('success', 'Faktor emisi berhasil dihapus.');
    }

    /** Daftar kategori: faktor_kategori tiap item config('cbam.kategori') + BBM. */
    private function kategoriList(): array
    {
        return collect(config('cbam.kategori', []))
            ->pluck('faktor_kategori')
            ->filter()
            ->merge(self::EXTRA_KATEGORI)
            ->unique()
            ->values()
            ->all();
    }

    private function validated(Request $request, ?EmissionFactor $factor = null): array
    {
        $isPenyerapan = $request->input('kategori') === self::PENYERAPAN;

        $rules = [
            'kategori'    => ['required', Rule::in($this->kategoriList())],
            'nama_faktor' => [
                'required', 'string', 'max:255',
                Rule::unique('emission_factors', 'nama_faktor')
                    ->where('kategori', $request->input('kategori'))
                    ->ignore($factor?->id),
            ],
            'satuan'      => ['required', 'string', 'max:50'],
            'nilai_emisi' => $isPenyerapan
                ? ['required', 'numeric', 'max:0']
                : ['required', 'numeric', 'min:0'],
            'scope'       => $isPenyerapan
                ? ['nullable']
                : ['required', Rule::in([1, 2, 3])],
        ];

        $messages = [
            'kategori.required'    => 'Kategori wajib dipilih.',
            'kategori.in'          => 'Kategori tidak valid.',
            'nama_faktor.required' => 'Nama faktor wajib diisi.',
            'nama_faktor.max'      => 'Nama faktor maksimal 255 karakter.',
            'nama_faktor.unique'   => 'Kombinasi kategori dan nama faktor sudah ada.',
            'satuan.required'      => 'Satuan wajib diisi.',
            'satuan.max'           => 'Satuan maksimal 50 karakter.',
            'nilai_emisi.required' => 'Nilai emisi wajib diisi.',
            'nilai_emisi.numeric'  => 'Nilai emisi harus berupa angka.',
            'nilai_emisi.min'      => 'Nilai emisi tidak boleh negatif.',
            'nilai_emisi.max'      => 'Nilai emisi penyerapan harus negatif atau 0.',
            'scope.required'       => 'Scope wajib dipilih (1, 2, atau 3).',
            'scope.in'             => 'Scope harus 1, 2, atau 3.',
        ];

        $data = $request->validate($rules, $messages);

        // Penyerapan: scope selalu null
        $data['scope'] = $isPenyerapan ? null : (int) $data['scope'];

        return $data;
    }
}