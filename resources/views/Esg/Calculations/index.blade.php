
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Perhitungan Emisi Tidak Langsung
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Rekapitulasi emisi Scope 3 dari transportasi lokal dan logistik ekspor.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi hasil hitung ulang --}}
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Ringkasan keseluruhan --}}
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Jumlah Entri</p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-800">
                        {{ number_format($totalEntri, 0, ',', '.') }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">
                        Seluruh aktivitas Scope 3
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Total Aktivitas</p>
                    <h3 class="mt-2 text-2xl font-bold text-gray-800">
                        {{ number_format($totalTonKm, 4, ',', '.') }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">
                        Ton-km
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-sm text-gray-500">Total Emisi Scope 3</p>
                    <h3 class="mt-2 text-2xl font-bold text-green-600">
                        {{ number_format($totalEmisi, 4, ',', '.') }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-400">
                        kg CO₂e
                    </p>
                </div>

            </div>

            {{-- Filter tanggal --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800">
                        Filter Periode
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Batasi rekap dan proses hitung ulang berdasarkan tanggal aktivitas.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('esg.calculations.index') }}"
                      class="grid grid-cols-1 gap-4 md:grid-cols-3">

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

                        <a href="{{ route('esg.calculations.index') }}"
                           class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Tombol hitung ulang --}}
            <div class="flex flex-col justify-between gap-4 rounded-xl border border-green-100 bg-green-50 p-6 sm:flex-row sm:items-center">
                <div>
                    <h3 class="font-semibold text-green-900">
                        Hitung Ulang Emisi Scope 3
                    </h3>
                    <p class="mt-1 text-sm text-green-700">
                        Perbarui total emisi berdasarkan jumlah aktivitas dan faktor emisi yang digunakan.
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('esg.calculations.recalculate') }}"
                      onsubmit="return confirm('Apakah kamu yakin ingin menghitung ulang seluruh emisi Scope 3 pada periode yang dipilih?')">

                    @csrf

                    <input type="hidden"
                           name="tanggal_mulai"
                           value="{{ $filters['tanggal_mulai'] ?? '' }}">

                    <input type="hidden"
                           name="tanggal_selesai"
                           value="{{ $filters['tanggal_selesai'] ?? '' }}">

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-green-700 px-5 py-3 text-sm font-semibold text-white hover:bg-green-800">
                        Hitung Ulang Emisi
                    </button>
                </form>
            </div>

            {{-- Rekap per lahan --}}
            <div class="space-y-6">

                @forelse ($landGroups as $landId => $rows)
                    @php
                        $landName = $rows->first()->nama_lahan;
                        $landEntries = $rows->sum('jumlah_entri');
                        $landTonKm = $rows->sum('total_ton_km');
                        $landEmission = $rows->sum('total_emisi');
                    @endphp

                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                        <div class="border-b border-gray-200 bg-gray-50 p-5">
                            <h3 class="font-semibold text-gray-800">
                                {{ $landName }}
                            </h3>

                            <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div>
                                    <p class="text-xs text-gray-500">Jumlah Entri</p>
                                    <p class="mt-1 font-semibold text-gray-800">
                                        {{ number_format($landEntries, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">Total Ton-km</p>
                                    <p class="mt-1 font-semibold text-gray-800">
                                        {{ number_format($landTonKm, 4, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500">Total Emisi Lahan</p>
                                    <p class="mt-1 font-semibold text-green-700">
                                        {{ number_format($landEmission, 4, ',', '.') }} kg CO₂e
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-white">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Kategori
                                        </th>
                                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                            Faktor Emisi
                                        </th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Nilai Faktor
                                        </th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Jumlah Entri
                                        </th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Total Ton-km
                                        </th>
                                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                            Total Emisi
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($rows as $row)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-5 py-4 text-sm text-gray-700">
                                                {{ $row->kategori_aktivitas === 'transportasi_lokal'
                                                    ? 'Transportasi Lokal'
                                                    : 'Logistik Ekspor' }}
                                            </td>

                                            <td class="px-5 py-4 text-sm font-medium text-gray-800">
                                                {{ $row->nama_faktor }}
                                            </td>

                                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-600">
                                                {{ number_format($row->nilai_emisi, 6, ',', '.') }}
                                                <span class="text-xs text-gray-400">
                                                    {{ $row->satuan }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4 text-right text-sm text-gray-700">
                                                {{ number_format($row->jumlah_entri, 0, ',', '.') }}
                                            </td>

                                            <td class="px-5 py-4 text-right text-sm text-gray-700">
                                                {{ number_format($row->total_ton_km, 4, ',', '.') }}
                                            </td>

                                            <td class="px-5 py-4 text-right text-sm font-semibold text-gray-800">
                                                {{ number_format($row->total_emisi, 4, ',', '.') }}
                                                <span class="text-xs font-normal text-gray-400">
                                                    kg CO₂e
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                @empty
                    <div class="rounded-xl border border-gray-200 bg-white px-6 py-12 text-center shadow-sm">
                        <h3 class="font-semibold text-gray-700">
                            Belum Ada Data Emisi Scope 3
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Data transportasi lokal dan logistik ekspor akan muncul
                            setelah Data Inputter menyimpan aktivitas.
                        </p>
                    </div>
                @endforelse

            </div>

            {{-- Informasi perhitungan --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-5">
                <h3 class="font-semibold text-blue-900">
                    Informasi Perhitungan
                </h3>

                <p class="mt-2 text-sm text-blue-800">
                    Perhitungan awal dilakukan ketika Data Inputter menyimpan aktivitas.
                    Fitur hitung ulang pada halaman ini digunakan untuk menyelaraskan
                    kembali total emisi dengan faktor emisi yang tersimpan.
                </p>

                <div class="mt-3 rounded-lg bg-white p-3 text-sm text-gray-700">
                    <p>
                        <strong>Jumlah aktivitas</strong>
                        = Jarak (km) × Muatan (ton)
                    </p>
                    <p class="mt-1">
                        <strong>Total emisi</strong>
                        = Jumlah aktivitas × Nilai faktor emisi
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
