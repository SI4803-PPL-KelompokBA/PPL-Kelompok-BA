<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Tambah Data Penyerapan Karbon Alam
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Catat aktivitas pengelolaan lahan yang berkontribusi terhadap penyimpanan karbon.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('inputter.emissions.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-green-700">
                    ← Kembali ke Daftar Data
                </a>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 bg-gray-50 px-6 py-5">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Formulir Aktivitas Penyerapan Karbon
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Lengkapi informasi aktivitas sesuai kondisi aktual di lahan pertanian.
                    </p>
                </div>

                <form method="POST"
                    action="{{ route('inputter.land-carbon-data.store') }}"
                    class="space-y-8 p-6">

                    @csrf

                    <div>
                        <h4 class="mb-4 border-b border-gray-100 pb-2 font-semibold text-gray-800">
                            Informasi Aktivitas
                        </h4>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="land_id" class="mb-2 block text-sm font-medium text-gray-700">
                                    Lahan <span class="text-red-500">*</span>
                                </label>

                                <select name="land_id" id="land_id" required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">
                                    <option value="">Pilih Lahan</option>

                                    @foreach ($lands as $land)
                                        <option value="{{ $land->id }}"
                                            @selected(old('land_id') == $land->id)>
                                            {{ $land->nama_lahan }} - {{ $land->lokasi }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('land_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>


                        <div>
                            <label for="tanggal_aktivitas" class="mb-2 block text-sm font-medium text-gray-700">
                                Periode Aktivitas (Bulan/Tahun) <span class="text-red-500">*</span>
                            </label>

                            <input type="month"
                                name="tanggal_aktivitas"
                                id="tanggal_aktivitas"
                                value="{{ old('tanggal_aktivitas') }}"
                                max="{{ now()->format('Y-m') }}"
                                required
                                class="w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">

                            @error('tanggal_aktivitas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        </div>
                    </div>

                    <div>
                        <h4 class="mb-4 border-b border-gray-100 pb-2 font-semibold text-gray-800">
                            Data Aktivitas Penyerapan Karbon
                        </h4>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="trees_planted" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jumlah Pohon Ditanam <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input type="number"
                                        name="trees_planted"
                                        id="trees_planted"
                                        value="{{ old('trees_planted', 0) }}"
                                        min="0"
                                        step="1"
                                        required
                                        class="w-full rounded-lg border-gray-300 pr-20 text-sm focus:border-green-600 focus:ring-green-600">

                                    <span class="absolute inset-y-0 right-3 flex items-center text-sm text-gray-400">
                                        Pohon
                                    </span>
                                </div>

                                @error('trees_planted')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cover_crop_area" class="mb-2 block text-sm font-medium text-gray-700">
                                    Luas Tanaman Penutup Tanah <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input type="number"
                                        name="cover_crop_area"
                                        id="cover_crop_area"
                                        value="{{ old('cover_crop_area', 0) }}"
                                        min="0"
                                        step="0.01"
                                        required
                                        class="w-full rounded-lg border-gray-300 pr-16 text-sm focus:border-green-600 focus:ring-green-600">

                                    <span class="absolute inset-y-0 right-3 flex items-center text-sm text-gray-400">
                                        Hektare
                                    </span>
                                </div>

                                @error('cover_crop_area')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="tillage_method" class="mb-2 block text-sm font-medium text-gray-700">
                                    Metode Pengolahan Tanah <span class="text-red-500">*</span>
                                </label>

                                <select name="tillage_method" id="tillage_method" required
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">

                                    <option value="">Pilih Metode Pengolahan Tanah</option>

                                    <option value="no_till" @selected(old('tillage_method') == 'no_till')>
                                        Tanpa Pengolahan Tanah (No-Till)
                                    </option>

                                    <option value="minimum_tillage" @selected(old('tillage_method') == 'minimum_tillage')>
                                        Pengolahan Tanah Minimum (Minimum Tillage)
                                    </option>

                                    <option value="conventional_tillage" @selected(old('tillage_method') == 'conventional_tillage')>
                                        Pengolahan Tanah Konvensional (Conventional Tillage)
                                    </option>
                                </select>

                                @error('tillage_method')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="biochar_amount" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jumlah Biochar <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input type="number"
                                        name="biochar_amount"
                                        id="biochar_amount"
                                        value="{{ old('biochar_amount', 0) }}"
                                        min="0"
                                        step="0.01"
                                        required
                                        class="w-full rounded-lg border-gray-300 pr-16 text-sm focus:border-green-600 focus:ring-green-600">

                                    <span class="absolute inset-y-0 right-3 flex items-center text-sm text-gray-400">
                                        Kg
                                    </span>
                                </div>

                                @error('biochar_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="compost_amount" class="mb-2 block text-sm font-medium text-gray-700">
                                    Jumlah Kompos <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input type="number"
                                        name="compost_amount"
                                        id="compost_amount"
                                        value="{{ old('compost_amount', 0) }}"
                                        min="0"
                                        step="0.01"
                                        required
                                        class="w-full rounded-lg border-gray-300 pr-16 text-sm focus:border-green-600 focus:ring-green-600">

                                    <span class="absolute inset-y-0 right-3 flex items-center text-sm text-gray-400">
                                        Kg
                                    </span>
                                </div>

                                @error('compost_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div>
                        <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                            Catatan Tambahan
                        </label>

                        <textarea name="notes"
                            id="notes"
                            rows="4"
                            maxlength="2000"
                            placeholder="Tambahkan keterangan aktivitas jika diperlukan..."
                            class="w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">

                        <a href="{{ route('inputter.emissions.index') }}"
                            class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                            class="rounded-lg bg-green-700 px-6 py-2.5 text-sm font-semibold text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2">
                            Simpan Data
                        </button>

                    </div>

                </form>
            </div>

            <div class="mt-5 rounded-lg border border-blue-100 bg-blue-50 p-4">
                <p class="text-sm text-blue-800">
                    <strong>Informasi:</strong> Data yang dimasukkan digunakan sebagai catatan aktivitas
                    pengelolaan karbon pada lahan pertanian. Estimasi penyerapan karbon belum dihitung
                    pada tahap pencatatan ini.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
