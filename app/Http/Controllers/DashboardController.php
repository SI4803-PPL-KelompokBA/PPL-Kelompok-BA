<?php

namespace App\Http\Controllers;

use App\Models\Emission;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $role = $request->user()->role;
        $data = [];

        if ($role === 'data_inputter') {
            $data['perKategori'] = Emission::selectRaw('kategori_aktivitas, COUNT(*) as jumlah, SUM(total_emisi_kotor) as total')
                ->groupBy('kategori_aktivitas')->get()->keyBy('kategori_aktivitas');
        }

        return view('dashboard', ['role' => $role] + $data);
    }
}