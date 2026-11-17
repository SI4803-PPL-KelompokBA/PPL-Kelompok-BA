<?php

namespace App\Http\Controllers\Esg;

use App\Http\Controllers\Controller;
use App\Models\Emission;
use App\Models\Land;
use Illuminate\Http\Request;

class TransportEmissionController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'land_id' => ['nullable', 'integer', 'exists:lands,id'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
        ]);

        $kategori = [
            'transportasi_lokal',
            'logistik_ekspor',
        ];

        $query = Emission::whereIn('kategori_aktivitas', $kategori)
            ->when(
                $filters['land_id'] ?? null,
                fn ($q, $v) => $q->where('land_id', $v)
            )
            ->when(
                $filters['tanggal_mulai'] ?? null,
                fn ($q, $v) => $q->whereDate('tanggal_aktivitas', '>=', $v)
            )
            ->when(
                $filters['tanggal_selesai'] ?? null,
                fn ($q, $v) => $q->whereDate('tanggal_aktivitas', '<=', $v)
            );

        $totalTransportasiLokal = (clone $query)
            ->where('kategori_aktivitas', 'transportasi_lokal')
            ->sum('total_emisi_kotor');

        $totalLogistikEkspor = (clone $query)
            ->where('kategori_aktivitas', 'logistik_ekspor')
            ->sum('total_emisi_kotor');

        $emissions = (clone $query)
            ->with(['land', 'factor'])
            ->latest('tanggal_aktivitas')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('esg.transport-emissions.index', [
            'emissions' => $emissions,
            'lands' => Land::orderBy('nama_lahan')->get(),
            'filters' => $filters,
            'totalTransportasiLokal' => $totalTransportasiLokal,
            'totalLogistikEkspor' => $totalLogistikEkspor,
            'totalEmisi' => $totalTransportasiLokal + $totalLogistikEkspor,
            'jumlahAktivitas' => (clone $query)->count(),
        ]);
    }
}
