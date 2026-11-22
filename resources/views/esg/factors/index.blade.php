<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Faktor Emisi</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <x-flash />
            <x-input-error :messages="$errors->get('hapus')" />

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <form method="GET" action="{{ route('esg.emission-factors.index') }}" class="flex items-center gap-2">
                        <select name="kategori" class="border-gray-300 rounded-md shadow-sm text-sm" onchange="this.form.submit()">
                            <option value="">Semua kategori</option>
                            @foreach ($kategoriList as $k)
                                <option value="{{ $k }}" @selected($filter === $k)>{{ $k }}</option>
                            @endforeach
                        </select>
                        @if ($filter)
                            <a href="{{ route('esg.emission-factors.index') }}" class="text-sm text-gray-600 underline">Reset</a>
                        @endif
                    </form>

                    <a href="{{ route('esg.emission-factors.create') }}">
                        <x-primary-button type="button">+ Faktor Emisi</x-primary-button>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600 border-b">
                                <th class="py-2 pr-4">Kategori</th>
                                <th class="py-2 pr-4">Nama faktor</th>
                                <th class="py-2 pr-4">Scope</th>
                                <th class="py-2 pr-4 text-right">Nilai</th>
                                <th class="py-2 pr-4">Satuan</th>
                                <th class="py-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($factors as $f)
                                <tr class="border-b">
                                    <td class="py-2 pr-4">{{ $f->kategori }}</td>
                                    <td class="py-2 pr-4">{{ $f->nama_faktor }}</td>
                                    <td class="py-2 pr-4">{{ $f->scope ?? '—' }}</td>
                                    <td class="py-2 pr-4 text-right">{{ rtrim(rtrim(number_format($f->nilai_emisi, 6, ',', '.'), '0'), ',') }}</td>
                                    <td class="py-2 pr-4">{{ $f->satuan }}</td>
                                    <td class="py-2 whitespace-nowrap">
                                        <a href="{{ route('esg.emission-factors.edit', $f) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('esg.emission-factors.destroy', $f) }}" class="inline"
                                              onsubmit="return confirm('Hapus faktor ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-3 text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-500">Belum ada faktor emisi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $factors->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>