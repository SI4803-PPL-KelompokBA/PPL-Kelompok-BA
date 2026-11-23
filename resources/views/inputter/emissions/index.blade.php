<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Input Emisi</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <x-flash />

        <div class="flex flex-wrap gap-2">
            @foreach ($kategoris as $slug => $k)
                <a href="{{ route('inputter.emissions.create', $slug) }}"
                   class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">+ {{ $k['label'] }}</a>
            @endforeach
        </div>

        <form method="GET" class="flex gap-2">
            <select name="kategori" class="border-gray-300 rounded-md text-sm">
                <option value="">Semua kategori</option>
                @foreach ($kategoris as $slug => $k)
                    <option value="{{ $slug }}" @selected(request('kategori') === $slug)>{{ $k['label'] }}</option>
                @endforeach
            </select>
            <select name="land_id" class="border-gray-300 rounded-md text-sm">
                <option value="">Semua lahan</option>
                @foreach ($lands as $l)
                    <option value="{{ $l->id }}" @selected(request('land_id') == $l->id)>{{ $l->nama_lahan }}</option>
                @endforeach
            </select>
            <x-secondary-button type="submit">Filter</x-secondary-button>
        </form>

        <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-3">Tanggal</th><th class="p-3">Lahan</th><th class="p-3">Kategori</th>
                        <th class="p-3">Faktor</th><th class="p-3 text-right">Jumlah</th>
                        <th class="p-3 text-right">Emisi (kg CO₂e)</th><th class="p-3">Bukti</th><th class="p-3"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($emissions as $e)
                    <tr class="border-t">
                        <td class="p-3">{{ $e->tanggal_aktivitas->format('d/m/Y') }}</td>
                        <td class="p-3">{{ $e->land->nama_lahan }}</td>
                        <td class="p-3">{{ $kategoris[$e->kategori_aktivitas]['label'] ?? $e->kategori_aktivitas }}</td>
                        <td class="p-3">{{ $e->factor->nama_faktor }}</td>
                        <td class="p-3 text-right">{{ number_format($e->jumlah_penggunaan, 2) }}</td>
                        <td class="p-3 text-right {{ $e->total_emisi_kotor < 0 ? 'text-green-600' : '' }}">
                            {{ number_format($e->total_emisi_kotor, 2) }}
                        </td>
                        <td class="p-3">
                            @if ($e->bukti_dokumen)
                                <a class="text-indigo-600 underline" target="_blank"
                                   href="{{ Storage::url($e->bukti_dokumen) }}">Lihat</a>
                            @else - @endif
                        </td>
                        <td class="p-3">
                            <form method="POST" action="{{ route('inputter.emissions.destroy', $e) }}"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-6 text-center text-gray-500">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $emissions->links() }}
    </div>
</x-app-layout>