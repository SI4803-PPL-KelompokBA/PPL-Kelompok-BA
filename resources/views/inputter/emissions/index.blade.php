<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Input Emisi
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

        <x-flash />

        {{-- Tombol Input Berdasarkan Kategori --}}
        <div class="flex flex-wrap gap-2">
            @foreach ($kategoris as $slug => $k)
                <a
                    href="{{ route('inputter.emissions.create', $slug) }}"
                    class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm"
                >
                    + {{ $k['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Filter Data --}}
        <form method="GET" class="flex flex-wrap gap-2">

            <select
                name="kategori"
                class="border-gray-300 rounded-md text-sm"
            >
                <option value="">Semua kategori</option>

                @foreach ($kategoris as $slug => $k)
                    <option
                        value="{{ $slug }}"
                        @selected(request('kategori') === $slug)
                    >
                        {{ $k['label'] }}
                    </option>
                @endforeach
            </select>

            <select
                name="land_id"
                class="border-gray-300 rounded-md text-sm"
            >
                <option value="">Semua lahan</option>

                @foreach ($lands as $l)
                    <option
                        value="{{ $l->id }}"
                        @selected(request('land_id') == $l->id)
                    >
                        {{ $l->nama_lahan }}
                    </option>
                @endforeach
            </select>

            <x-secondary-button type="submit">
                Filter
            </x-secondary-button>

        </form>

        {{-- Tabel Riwayat Emisi --}}
        <div class="bg-white shadow sm:rounded-lg overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-left">
                    <tr>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Lahan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Faktor</th>
                        <th class="p-3 text-right">Jumlah</th>
                        <th class="p-3 text-right">Emisi (kg CO₂e)</th>
                        <th class="p-3">Bukti</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($emissions as $e)

                        <tr class="border-t">

                            {{-- Tanggal Aktivitas --}}
                            <td class="p-3">
                                {{ $e->tanggal_aktivitas->format('d/m/Y') }}
                            </td>

                            {{-- Nama Lahan --}}
                            <td class="p-3">
                                {{ $e->land->nama_lahan }}
                            </td>

                            {{-- Kategori Aktivitas --}}
                            <td class="p-3">
                                {{ $kategoris[$e->kategori_aktivitas]['label'] ?? $e->kategori_aktivitas }}
                            </td>

                            {{-- Faktor Emisi --}}
                            <td class="p-3">
                                {{ $e->factor->nama_faktor }}
                            </td>

                            {{-- Jumlah Penggunaan --}}
                            <td class="p-3 text-right">
                                {{ number_format($e->jumlah_penggunaan, 2) }}
                            </td>

                            {{-- Total Emisi --}}
                            <td class="p-3 text-right {{ $e->total_emisi_kotor < 0 ? 'text-green-600' : '' }}">
                                {{ number_format($e->total_emisi_kotor, 2) }}
                            </td>

                            {{-- Bukti Dokumen --}}
                            <td class="p-3 min-w-48">

                                {{-- Jika bukti sudah tersedia --}}
                                @if ($e->bukti_dokumen)

                                    <a
                                        class="text-indigo-600 underline block mb-2"
                                        href="{{ Storage::url($e->bukti_dokumen) }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        Lihat bukti
                                    </a>

                                @else

                                    <span class="text-gray-400 text-xs block mb-2">
                                        Belum ada bukti
                                    </span>

                                @endif

                                {{-- Form Upload / Ganti Bukti --}}
                                <form
                                    method="POST"
                                    enctype="multipart/form-data"
                                    action="{{ route('inputter.emissions.bukti.update', $e) }}"
                                    class="space-y-2"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <input
                                        type="file"
                                        name="bukti_dokumen"
                                        accept=".pdf,.jpg,.jpeg,.png"
                                        required
                                        class="block w-full text-xs text-gray-600"
                                    >

                                    <button
                                        type="submit"
                                        class="text-indigo-600 hover:text-indigo-800 text-sm font-medium"
                                    >
                                        {{ $e->bukti_dokumen ? 'Ganti bukti' : 'Unggah bukti' }}
                                    </button>

                                </form>

                                {{-- Error Validasi Khusus Setiap Entri --}}
                                @if ($errors->hasBag('bukti_' . $e->id))

                                    <x-input-error
                                        :messages="$errors->getBag('bukti_' . $e->id)->get('bukti_dokumen')"
                                        class="mt-2"
                                    />

                                @endif

                            </td>

                            {{-- Aksi Hapus --}}
                            <td class="p-3">

                                <form
                                    method="POST"
                                    action="{{ route('inputter.emissions.destroy', $e) }}"
                                    onsubmit="return confirm('Hapus data ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button class="text-red-600 hover:text-red-800">
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="8"
                                class="p-6 text-center text-gray-500"
                            >
                                Belum ada data.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        {{ $emissions->links() }}

    </div>
</x-app-layout>