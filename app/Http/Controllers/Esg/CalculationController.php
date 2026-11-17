<?php

namespace App\Http\Controllers\Esg;

use App\Http\Controllers\Controller;
use App\Models\Emission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalculationController extends Controller
{
    private const CATEGORIES = [
        'transportasi_lokal',
        'logistik_ekspor',
    ];

    /**
     * Filter tanggal digunakan bersama oleh rekap dan hitung ulang.
     */
    private function validateFilters(Request $request): array
    {
        return $request->validate([
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
        ]);
    }

    /**
     * Menampilkan rekapitulasi emisi Scope 3.
     */
    public function index(Request $request)
    {
        $filters = $this->validateFilters($request);

        $summary = DB::table('emissions as e')
            ->join('lands as l', 'l.id', '=', 'e.land_id')
            ->join('emission_factors as ef', 'ef.id', '=', 'e.emission_factor_id')
            ->whereIn('e.kategori_aktivitas', self::CATEGORIES)
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn ($q, $date) => $q->whereDate('e.tanggal_aktivitas', '>=', $date)
            )
            ->when(
                $filters['tanggal_selesai'] ?? null,
                fn ($q, $date) => $q->whereDate('e.tanggal_aktivitas', '<=', $date)
            )
            ->select([
                'e.land_id',
                'l.nama_lahan',
                'e.kategori_aktivitas',
                'e.emission_factor_id',
                'ef.nama_faktor',
                'ef.nilai_emisi',
                'ef.satuan',
            ])
            ->selectRaw('COUNT(e.id) as jumlah_entri')
            ->selectRaw('SUM(e.jumlah_penggunaan) as total_ton_km')
            ->selectRaw('SUM(e.total_emisi_kotor) as total_emisi')
            ->groupBy([
                'e.land_id',
                'l.nama_lahan',
                'e.kategori_aktivitas',
                'e.emission_factor_id',
                'ef.nama_faktor',
                'ef.nilai_emisi',
                'ef.satuan',
            ])
            ->orderBy('l.nama_lahan')
            ->orderBy('e.kategori_aktivitas')
            ->orderBy('ef.nama_faktor')
            ->get();

        // Pengelompokan akhir: per lahan.
        // Di dalam setiap lahan sudah dikelompokkan per kategori dan faktor.
        $landGroups = $summary->groupBy('land_id');

        return view('esg.calculations.index', [
            'landGroups' => $landGroups,
            'filters' => $filters,
            'totalEntri' => $summary->sum('jumlah_entri'),
            'totalTonKm' => $summary->sum('total_ton_km'),
            'totalEmisi' => $summary->sum('total_emisi'),
        ]);
    }

    /**
     * Menghitung ulang emisi Scope 3 dari faktor emisi yang digunakan.
     */
    public function recalculate(Request $request)
    {
        $filters = $this->validateFilters($request);

        $updated = 0;

        $query = Emission::query()
            ->whereIn('kategori_aktivitas', self::CATEGORIES)
            ->with('factor')
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn ($q, $date) => $q->whereDate('tanggal_aktivitas', '>=', $date)
            )
            ->when(
                $filters['tanggal_selesai'] ?? null,
                fn ($q, $date) => $q->whereDate('tanggal_aktivitas', '<=', $date)
            );

        // TODO Sprint 2: kecualikan entri yang sudah termasuk laporan
        // yang disetujui agar tidak ikut dihitung ulang.

        $query->chunkById(200, function ($emissions) use (&$updated) {
            foreach ($emissions as $emission) {
                if (! $emission->factor) {
                    continue;
                }

                $calculated = round(
                    (float) $emission->jumlah_penggunaan
                    * (float) $emission->factor->nilai_emisi,
                    4
                );

                if ((float) $emission->total_emisi_kotor !== $calculated) {
                    $emission->update([
                        'total_emisi_kotor' => $calculated,
                    ]);

                    $updated++;
                }
            }
        });

        return redirect()
            ->route('esg.calculations.index', $filters)
            ->with('status', "{$updated} entri diperbarui.");
    }
}
