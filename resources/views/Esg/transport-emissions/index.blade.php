
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Perhitungan Emisi Transportasi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Rekapitulasi emisi transportasi lokal dan logistik ekspor.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('status'))
                <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Ringkasan Emisi --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Total Emisi Transportasi
                    </p>
                    <h3 class="mt-3 text-2xl font-bold text-gray-800">
                        {{ number_format($totalEmisi, 4, ',', '.') }}
                    </h3>
                    <p class="mt-2 text-xs text-gray-400">
                        Gabungan kedua kategori aktivitas
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Transportasi Lokal
                    </p>
                    <h3 class="mt-3 text-2xl font-bold text-green-600">
                        {{ number_format($totalTransportasiLokal, 4, ',', '.') }}
                    </h3>
                    <p class="mt-2 text-xs text-gray-400">
                        Total emisi aktivitas transportasi lokal
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">
                        Logistik Ekspor
                    </p>
                    <h3 class="mt-3 text-2xl font-bold text-blue-600">
                        {{ number_format($totalLogistikEkspor, 4, ',', '.') }}
                    </h3>
                    <p class="mt-2 text-xs text-gray-400">
                        Total emisi aktivitas logistik ekspor
                    </p>
                </div>

            </div>

            {{-- Filter Data --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800">
                        Filter Aktivitas
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Pilih lahan dan periode untuk melihat rekapitulasi emisi.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('esg.transport-emissions.index') }}"
                      class="grid grid-cols-1 gap-4 md:grid-cols-4">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Lahan
                        </label>
                        <select name="land_id"
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">Semua Lahan</option>
                            @foreach ($lands as $land)
                                <option value="{{ $land->id }}"
                                    @selected(($filters['land_id'] ?? '') == $land->id)>
                                    {{ $land->nama_lahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Tanggal Mulai
                        </label>
                        <input type="date"
                               name="tanggal_mulai"
                               value="{{ $filters['tanggal_mulai'] ?? '' }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Tanggal Selesai
                        </label>
                        <input type="date"
                               name="tanggal_selesai"
                               value="{{ $filters['tanggal_selesai'] ?? '' }}"
                               class="w-full rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                            Terapkan
                        </button>

                        <a href="{{ route('esg.transport-emissions.index') }}"
                           class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tabel Aktivitas --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col justify-between gap-2 border-b border-gray-200 p-6 sm:flex-row sm:items-center">
                    <div>
                        <h3 class="font-semibold text-gray-800">
                            Detail Perhitungan Emisi
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $jumlahAktivitas }} aktivitas ditemukan.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Lahan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Aktivitas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Faktor Emisi
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Jarak × Muatan
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Jumlah Aktivitas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Total Emisi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($emissions as $emission)
                                @php
                                    $detail = $emission->detail_tambahan ?? [];
                                    $isLokal = $emission->kategori_aktivitas === 'transportasi_lokal';
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ $emission->tanggal_aktivitas->format('d/m/Y') }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $emission->land->nama_lahan ?? '-' }}
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium
                                            {{ $isLokal ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $isLokal ? 'Transportasi Lokal' : 'Logistik Ekspor' }}
                                        </span>

                                        <p class="mt-1 text-xs text-gray-500">
                                            @if ($isLokal)
                                                {{ $detail['asal'] ?? '-' }}
                                                →
                                                {{ $detail['tujuan'] ?? '-' }}
                                            @else
                                                {{ $detail['pelabuhan_asal'] ?? '-' }}
                                                →
                                                {{ $detail['pelabuhan_tujuan'] ?? '-' }}
                                            @endif
                                        </p>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $emission->factor->nama_faktor ?? '-' }}

                                        @if ($emission->factor)
                                            <p class="mt-1 text-xs text-gray-500">
                                                Faktor:
                                                {{ number_format((float) $emission->factor->nilai_emisi, 6, ',', '.') }}
                                                {{ $emission->factor->satuan }}
                                            </p>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ number_format((float) ($detail['jarak_km'] ?? 0), 2, ',', '.') }}
                                        km ×
                                        {{ number_format((float) ($detail['muatan_ton'] ?? 0), 2, ',', '.') }}
                                        ton
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                        {{ number_format((float) $emission->jumlah_penggunaan, 4, ',', '.') }}
                                        ton-km
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-semibold text-gray-800">
                                        {{ number_format((float) $emission->total_emisi_kotor, 4, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"
                                        class="px-6 py-12 text-center text-sm text-gray-500">
                                        Belum ada data transportasi lokal atau logistik ekspor
                                        untuk filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $emissions->links() }}
                </div>
            </div>

            {{-- Informasi Perhitungan --}}
            <div class="rounded-xl border border-green-100 bg-green-50 p-5">
                <h3 class="font-semibold text-green-800">
                    Informasi Perhitungan Emisi
                </h3>

                <p class="mt-2 text-sm text-green-700">
                    Jumlah aktivitas dihitung dari jarak perjalanan dikalikan
                    dengan berat muatan. Total emisi diperoleh dengan mengalikan
                    jumlah aktivitas tersebut dengan faktor emisi yang dipilih.
                </p>

                <div class="mt-3 rounded-lg bg-white p-3 text-sm text-gray-700">
                    <p><strong>Jumlah aktivitas</strong> = Jarak (km) × Muatan (ton)</p>
                    <p class="mt-1"><strong>Total emisi</strong> = Jumlah aktivitas × Faktor emisi</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
