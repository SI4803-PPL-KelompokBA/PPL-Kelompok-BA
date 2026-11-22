<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Faktor Emisi</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="rounded-md bg-yellow-50 p-4 text-sm text-yellow-800">
                Perubahan nilai tidak mengubah data emisi yang sudah tercatat sampai dilakukan perhitungan ulang.
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('esg.emission-factors.update', $factor) }}">
                    @method('PUT')
                    @include('esg.factors._form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>