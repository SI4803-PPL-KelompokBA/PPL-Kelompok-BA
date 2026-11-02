<?php

namespace App\Http\Controllers;

use App\Models\ExportLogistic;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    public function index()
    {
        $data = ExportLogistic::all();
        return view('logistics.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'processing_facility' => 'required|string|max:255',
            'origin_port' => 'required|string|max:255',
            'destination_port' => 'required|string|max:255',
            'transport_mode' => 'required|string|max:255',
            'distance_km' => 'required|numeric',
            'cargo_weight_ton' => 'required|numeric',
        ]);

        ExportLogistic::create($request->all());

        return redirect()->back()->with('success', 'Data logistik ekspor berhasil disimpan!');
    }
}