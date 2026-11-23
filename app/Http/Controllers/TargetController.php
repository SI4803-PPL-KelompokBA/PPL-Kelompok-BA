<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        $year = (int) $request->query('year', now()->year);
        $year = max(2000, min($year, now()->year + 10));

        $previousYear = $year - 1;

        $lands = Land::query()
            ->withSum([
                'emissions as baseline_emission' => function ($query) use ($previousYear) {
                    $query->whereYear('tanggal_aktivitas', $previousYear);
                }
            ], 'total_emisi_kotor')
            ->withSum([
                'emissions as current_emission' => function ($query) use ($year) {
                    $query->whereYear('tanggal_aktivitas', $year);
                }
            ], 'total_emisi_kotor')
            ->get()
            ->map(function ($land) {
                $baseline = $land->baseline_emission;
                $current = $land->current_emission;
                $target = (float) ($land->target_pengurangan_emisi ?? 0);

                $reduction = null;
                $progress = null;

                if (
                    $baseline !== null &&
                    $current !== null &&
                    (float) $baseline > 0
                ) {
                    $reduction = (
                        ((float) $baseline - (float) $current)
                        / (float) $baseline
                    ) * 100;

                    if ($target > 0) {
                        $progress = max(
                            0,
                            min(100, ($reduction / $target) * 100)
                        );
                    }
                }

                $land->baseline_emission = $baseline;
                $land->current_emission = $current;
                $land->reduction_percentage = $reduction;
                $land->progress_percentage = $progress;

                return $land;
            });

        return view('targets.index', compact(
            'lands',
            'year',
            'previousYear'
        ));
    }

    /**
     * CREATE dan UPDATE target.
     * Jika target belum ada, proses ini membuat target.
     * Jika target sudah ada, proses ini memperbarui target.
     */
    public function update(Request $request, Land $land)
    {
        $validated = $request->validateWithBag(
            'target_' . $land->id,
            [
                'target_pengurangan_emisi' => [
                    'required',
                    'numeric',
                    'gt:0',
                    'lte:100',
                ],
                'year' => [
                    'nullable',
                    'integer',
                    'min:2000',
                    'max:' . (now()->year + 10),
                ],
            ],
            [
                'target_pengurangan_emisi.required' => 'Target wajib diisi.',
                'target_pengurangan_emisi.numeric' => 'Target harus berupa angka.',
                'target_pengurangan_emisi.gt' => 'Target harus lebih dari 0%.',
                'target_pengurangan_emisi.lte' => 'Target maksimal 100%.',
            ]
        );

        $land->update([
            'target_pengurangan_emisi' =>
                $validated['target_pengurangan_emisi'],
        ]);

        return redirect()
            ->route('esg.targets.index', [
                'year' => $validated['year'] ?? now()->year,
            ])
            ->with('success', 'Target pengurangan emisi berhasil disimpan.');
    }

    /**
     * DELETE target.
     * Data kebun dan data emisi tetap dipertahankan.
     */
    public function destroy(Request $request, Land $land)
    {
        $land->update([
            'target_pengurangan_emisi' => null,
        ]);

        return redirect()
            ->route('esg.targets.index', [
                'year' => $request->input('year', now()->year),
            ])
            ->with('success', 'Target pengurangan emisi berhasil dihapus.');
    }
}
