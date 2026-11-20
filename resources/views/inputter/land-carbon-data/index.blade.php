
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Data Penyerapan Karbon Alam
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola pencatatan aktivitas penyerapan karbon pada lahan pertanian.
                </p>
            </div>

            <a href="{{ route('inputter.land-carbon-data.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-green-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                + Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <x-flash />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Data Aktivitas</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ $carbonData->total() }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Jumlah Pohon pada Halaman Ini</p>
                    <p class="mt-2 text-2xl font-bold text-green-700">
                        {{ number_format($carbonData->sum('trees_planted'), 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Pohon</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Biochar pada Halaman Ini</p>
                    <p class="mt-2 text-2xl font-bold text-gray-800">
                        {{ number_format((float) $carbonData->sum('biochar_amount'), 2, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-400">Kilogram</p>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <form method="GET"
                    action="{{ route('inputter.land-carbon-data.index') }}"
                    class="flex flex-col gap-3 sm:flex-row sm:items-end">

                    <div class="flex-1">
                        <label for="land_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Filter Berdasarkan Lahan
                        </label>

                        <select name="land_id" id="land_id"
                            class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-green-600 focus:ring-green-600">
                            <option value="">Semua Lahan</option>

                            @foreach ($lands as $land)
                                <option value="{{ $land->id }}"
                                    @selected(request('land_id') == $land->id)>
                                    {{ $land->nama_lahan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-800">
                            Filter
                        </button>

                        <a href="{{ route('inputter.land-carbon-data.index') }}"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="font-semibold text-gray-800">
                        Riwayat Data Penyerapan Karbon Alam
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Daftar aktivitas penyerapan karbon yang telah dicatat.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">No.</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Tanggal</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Lahan</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Pohon</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Tanaman Penutup</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Pengolahan Tanah</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Biochar (kg)</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold uppercase text-gray-500">Kompos (kg)</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-gray-500">Catatan</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold uppercase text-gray-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($carbonData as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        {{ $carbonData->firstItem() + $index }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">
                                        {{ $item->tanggal_aktivitas->format('d/m/Y') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-800">
                                        {{ $item->land->nama_lahan ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-700">
                                        {{ number_format($item->trees_planted, 0, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-700">
                                        {{ number_format((float) $item->cover_crop_area, 2, ',', '.') }} ha
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700">
                                        @switch($item->tillage_method)
                                            @case('no_till')
                                                Tanpa Olah Tanah
                                                @break
                                            @case('minimum_tillage')
                                                Olah Tanah Minimum
                                                @break
                                            @case('conventional_tillage')
                                                Olah Tanah Konvensional
                                                @break
                                            @default
                                                {{ $item->tillage_method }}
                                        @endswitch
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-700">
                                        {{ number_format((float) $item->biochar_amount, 2, ',', '.') }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-right text-sm text-gray-700">
                                        {{ number_format((float) $item->compost_amount, 2, ',', '.') }}
                                    </td>

                                    <td class="max-w-xs px-5 py-4 text-sm text-gray-600">
                                        {{ $item->notes ? \Illuminate\Support\Str::limit($item->notes, 45) : '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('inputter.land-carbon-data.show', $item->id) }}"
                                                class="rounded-lg bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-100">
                                                Detail
                                            </a>

                                            <a href="{{ route('inputter.land-carbon-data.edit', $item->id) }}"
                                                class="rounded-lg bg-amber-50 px-3 py-2 text-xs font-semibold text-amber-700 hover:bg-amber-100">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('inputter.land-carbon-data.destroy', $item->id) }}"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-5 py-12 text-center">
                                        <p class="font-medium text-gray-700">
                                            Belum ada data penyerapan karbon alam.
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Silakan tambahkan data aktivitas penyerapan karbon.
                                        </p>
                                        <a href="{{ route('inputter.land-carbon-data.create') }}"
                                            class="mt-4 inline-flex rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">
                                            + Tambah Data
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($carbonData->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4">
                        {{ $carbonData->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
