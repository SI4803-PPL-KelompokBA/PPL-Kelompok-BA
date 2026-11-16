<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if ($role === 'data_inputter')
            <a href="{{ route('inputter.emissions.index') }}" class="text-indigo-600 underline">Buka riwayat & form input</a>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4">
                @foreach (config('cbam.kategori') as $slug => $k)
                    <div class="bg-white p-4 shadow rounded">
                        <div class="text-gray-500 text-sm">{{ $k['label'] }}</div>
                        <div class="text-2xl font-bold">{{ $perKategori[$slug]->jumlah ?? 0 }} entri</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">Dashboard {{ str_replace('_', ' ', $role) }} akan dikerjakan di langkah berikutnya.</p>
        @endif
    </div>
</x-app-layout>