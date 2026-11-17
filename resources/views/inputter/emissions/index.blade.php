
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Riwayat Input Emisi
            </h2>
            <p class="text-sm text-gray-500">
                Kelola dan pantau seluruh aktivitas pencatatan emisi perkebunan.
            </p>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <x-flash />

        <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-gray-800">
                        Tambah Aktivitas Emisi
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Pilih kategori aktivitas untuk mulai mencatat data.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($kategoris as $slug => $category)
                        <a
                            href="{{ route('inputter.emissions.create', $slug) }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg"
                        >
                            + {{ $category['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-100 shadow-sm rounded-xl p-5">
            <h3 class="font-semibold text-gray-800 mb-4">
                Filter Data
            </h3>

            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <select
                    name="kategori"
                    class="border-gray-300 rounded-lg text-sm"
                >
                    <option value="">Semua kategori</option>

                    @foreach ($kategoris as $slug => $category)
                        <option
                            value="{{ $slug }}"
                            @selected(request('kategori') === $slug)
                        >
                            {{ $category['label'] }}
                        </option>
                    @endforeach
                </select>

                <select
                    name="land_id"
                    class="border-gray-300 rounded-lg text-sm"
                >
                    <option value="">Semua lahan</option>

                    @foreach ($lands as $land)
                        <option
                            value="{{ $land->id }}"
                            @selected(request('land_id') == $land->id)
                        >
                            {{ $land->nama_lahan }}
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button
                        type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg"
                    >
                        Terapkan Filter
                    </button>

                    <a
                        href="{{ route('inputter.emissions.index') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-600 text-sm rounded-lg hover:bg-gray-50"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">
                    Data Aktivitas Emisi
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Daftar aktivitas yang telah dicatat oleh Data Inputter.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="p-4 text-left">Tanggal</th>
                            <th class="p-4 text-left">Lahan</th>
                            <th class="p-4 text-left">Kategori</th>
                            <th class="p-4 text-left">Kendaraan / Faktor</th>
                            <th class="p-4 text-left">Informasi Aktivitas</th>
                            <th class="p-4 text-right">Emisi (kg CO₂e)</th>
                            <th class="p-4 text-center">Bukti</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($emissions as $emission)
                            <tr class="hover:bg-gray-50 align-top">

                                <td class="p-4 whitespace-nowrap">
                                    {{ $emission->tanggal_aktivitas->format('d/m/Y') }}
                                </td>

                                <td class="p-4">
                                    {{ $emission->land?->nama_lahan ?? '-' }}
                                </td>

                                <td class="p-4">
                                    {{ $kategoris[$emission->kategori_aktivitas]['label'] ?? $emission->kategori_aktivitas }}
                                </td>

                                <td class="p-4">
                                    {{ $emission->factor?->nama_faktor ?? '-' }}
                                    @if ($emission->factor)
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $emission->factor->nilai_emisi }}
                                            {{ $emission->factor->satuan }}
                                        </p>
                                    @endif
                                </td>

                                <td class="p-4 min-w-64">
                                    @if ($emission->kategori_aktivitas === 'transportasi_lokal')
                                        <div class="space-y-1">
                                            <p>
                                                <span class="text-gray-500">Asal:</span>
                                                {{ data_get($emission->detail_tambahan, 'asal') ?: $emission->land?->nama_lahan ?? '-' }}
                                            </p>

                                            <p>
                                                <span class="text-gray-500">Tujuan:</span>
                                                {{ data_get($emission->detail_tambahan, 'tujuan') ?: '-' }}
                                            </p>

                                            <p>
                                                <span class="text-gray-500">Jarak:</span>
                                                {{ number_format((float) data_get($emission->detail_tambahan, 'jarak_km', 0), 2) }} km
                                            </p>

                                            <p>
                                                <span class="text-gray-500">Muatan:</span>
                                                {{ number_format((float) data_get($emission->detail_tambahan, 'muatan_ton', 0), 2) }} ton
                                            </p>

                                            <p class="text-xs text-indigo-600 font-medium">
                                                {{ number_format($emission->jumlah_penggunaan, 4) }} ton-km
                                            </p>
                                        </div>
                                    @else
                                        <span class="text-gray-500">
                                            {{ number_format($emission->jumlah_penggunaan, 2) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="p-4 text-right whitespace-nowrap">
                                    <span class="font-semibold {{ $emission->total_emisi_kotor < 0 ? 'text-green-600' : 'text-gray-800' }}">
                                        {{ number_format($emission->total_emisi_kotor, 4) }}
                                    </span>
                                </td>

                                <td class="p-4 text-center">
                                    @if ($emission->bukti_dokumen)
                                        <a
                                            href="{{ Storage::url($emission->bukti_dokumen) }}"
                                            target="_blank"
                                            class="text-indigo-600 hover:underline"
                                        >
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <a
                                            href="{{ route('inputter.emissions.edit', $emission) }}"
                                            class="text-indigo-600 hover:text-indigo-800 font-medium"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('inputter.emissions.destroy', $emission) }}"
                                            onsubmit="return confirm('Apakah kamu yakin ingin menghapus data ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="text-red-600 hover:text-red-800 font-medium"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-gray-500">
                                    Belum ada aktivitas emisi yang dicatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-gray-100">
                {{ $emissions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
