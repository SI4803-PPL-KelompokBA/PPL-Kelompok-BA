<?php

namespace App\Http\Controllers;

use App\Models\ElectricityUsage;
use Illuminate\Http\Request;

class ElectricityController extends Controller
{
    public function index()
    {
        $data = ElectricityUsage::all();
        return view('electricity.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'kwh_consumed' => 'required|numeric',
            'record_date' => 'required|date',
        ]);

        ElectricityUsage::create($request->all());

        return redirect()->back()->with('success', 'Data konsumsi listrik berhasil disimpan!');
    }
}