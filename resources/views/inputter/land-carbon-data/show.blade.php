<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Data Penyerapan Karbon Alam
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Informasi lengkap aktivitas penyerapan karbon pada lahan.
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('inputter.land-carbon-data.index') }}"
                   class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>

                <a href="{{ route('inputter.land-carbon-data.edit', $item->id) }}"
                   class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    Edit Data
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $tillageLabels = [
            'no_till' => 'Tanpa Olah Tanah',
            'minimum_tillage' => 'Olah Tanah Minimum',
            'conventional_tillage' => 'Olah Tanah Konvensional',
        ];
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-5 text-lg font-semibold text-gray-800">
                    Informasi Lahan dan Aktivitas
                </h3>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Nama Lahan</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $item->land->nama_lahan ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Aktivitas</p>
                        <p class="mt-1 font-medium text-gray-800">
                            {{ $item->tanggal_aktivitas->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-5 text-lg font-semibold text-gray-800">
                    Data Penyerapan Karbon Alam
                </h3>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="rounded-lg bg-green-50 p-4">
                        <p class="text-sm text-gray-600">Jumlah Pohon</p>
                        <p class="mt-2 text-2xl font-bold text-green-700">
                            {{ number_format($item->trees_planted) }}
                        </p>
                        <p class="text-xs text-gray-500">Pohon</p>
                    </div>

                    <div class="rounded-lg bg-blue-50 p-4">
                        <p class="text-sm text-gray-600">Luas Tanaman Penutup</p>
                        <p class="mt-2 text-2xl font-bold text-blue-700">
                            {{ number_format((float) $item->cover_crop_area, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">Hektare (ha)</p>
                    </div>

                    <div class="rounded-lg bg-amber-50 p-4">
                        <p class="text-sm text-gray-600">Metode Pengolahan Tanah</p>
                        <p class="mt-2 text-lg font-bold text-amber-700">
                            {{ $tillageLabels[$item->tillage_method] ?? $item->tillage_method }}
                        </p>
                    </div>

                    <div class="rounded-lg bg-purple-50 p-4">
                        <p class="text-sm text-gray-600">Jumlah Biochar</p>
                        <p class="mt-2 text-2xl font-bold text-purple-700">
                            {{ number_format((float) $item->biochar_amount, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">Kilogram (kg)</p>
                    </div>

                    <div class="rounded-lg bg-orange-50 p-4">
                        <p class="text-sm text-gray-600">Jumlah Kompos</p>
                        <p class="mt-2 text-2xl font-bold text-orange-700">
                            {{ number_format((float) $item->compost_amount, 2, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">Kilogram (kg)</p>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 text-lg font-semibold text-gray-800">
                    Catatan Tambahan
                </h3>

                <p class="whitespace-pre-line text-sm leading-relaxed text-gray-600">{{ $item->notes ?: 'Tidak ada catatan tambahan.' }}</p>
            </div>

        </div>
    </div>
</x-app-layout>
