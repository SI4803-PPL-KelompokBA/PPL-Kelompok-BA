<?php

namespace App\Http\Controllers\Esg;

use App\Http\Controllers\Controller;
use App\Models\Land;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LandController extends Controller
{
    /**
     * Menampilkan daftar lahan.
     */
    public function index()
    {
        $lands = Land::withCount('emissions')
            ->latest()
            ->paginate(15);

        return view('esg.lands.index', compact('lands'));
    }

    /**
     * Menampilkan form tambah lahan.
     */
    public function create()
    {
        return view('esg.lands.create');
    }

    /**
     * Menyimpan data lahan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lahan' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'luas_area' => ['required', 'numeric', 'gt:0'],
            'target_pengurangan_emisi' => [
                'required', 'numeric', 'between:0,100'
            ],
            'latitude' => [
                'nullable', 'numeric', 'between:-90,90'
            ],
            'longitude' => [
                'nullable', 'numeric', 'between:-180,180'
            ],
            'status_operasional' => [
                'required', Rule::in(['aktif', 'nonaktif'])
            ],
        ]);

        Land::create($validated);

        return redirect()
            ->route('esg.lands.index')
            ->with('success', 'Data lahan berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit lahan.
     */
    public function edit(Land $land)
    {
        return view('esg.lands.edit', compact('land'));
    }

    /**
     * Memperbarui data lahan.
     */
    public function update(Request $request, Land $land)
    {
        $validated = $request->validate([
            'nama_lahan' => ['required', 'string', 'max:255'],
            'lokasi' => ['required', 'string', 'max:255'],
            'luas_area' => ['required', 'numeric', 'gt:0'],
            'target_pengurangan_emisi' => [
                'required', 'numeric', 'between:0,100'
            ],
            'latitude' => [
                'nullable', 'numeric', 'between:-90,90'
            ],
            'longitude' => [
                'nullable', 'numeric', 'between:-180,180'
            ],
            'status_operasional' => [
                'required', Rule::in(['aktif', 'nonaktif'])
            ],
        ]);

        $land->update($validated);

        return redirect()
            ->route('esg.lands.index')
            ->with('success', 'Data lahan berhasil diperbarui.');
    }

    /**
     * Menghapus lahan jika belum memiliki entri emisi atau laporan.
     */
    public function destroy(Land $land)
    {
        if ($land->emissions()->exists() || $land->reports()->exists()) {
            return redirect()
                ->route('esg.lands.index')
                ->withErrors([
                    'hapus' => 'Lahan tidak dapat dihapus karena sudah memiliki entri emisi atau laporan. Silakan ubah status operasional menjadi nonaktif.',
                ]);
        }

        $land->delete();

        return redirect()
            ->route('esg.lands.index')
            ->with('success', 'Data lahan berhasil dihapus.');
    }
}
