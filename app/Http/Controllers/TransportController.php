<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use App\Models\EmissionFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransportController extends Controller
{
    public function index()
    {
        $transports = Transport::with('emissionFactor')->latest()->get();
        $emissionFactors = EmissionFactor::all();

        return view('transports.index', compact('transports', 'emissionFactors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'emission_factor_id' => 'required|exists:emission_factors,id',
            'jarak_km'           => 'required|numeric|min:0',
            'muatan_ton'         => 'required|numeric|min:0',
            'bukti_dokumen'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        $filePath = null;
        if ($request->hasFile('bukti_dokumen')) {
            $filePath = $request->file('bukti_dokumen')->store('bukti_dokumen', 'public');
        }

        Transport::create([
            'emission_factor_id' => $request->emission_factor_id,
            'jarak_km'           => $request->jarak_km,
            'muatan_ton'         => $request->muatan_ton,
            'bukti_dokumen'      => $filePath,
        ]);

        return back()->with('success', 'Data transportasi dan dokumen bukti berhasil disimpan!');
    }

    public function destroy($id)
    {
        $transport = Transport::findOrFail($id);

        if ($transport->bukti_dokumen && Storage::disk('public')->exists($transport->bukti_dokumen)) {
            Storage::disk('public')->delete($transport->bukti_dokumen);
        }

        $transport->delete();

        return back()->with('success', 'Data transportasi berhasil dihapus!');
    }
}