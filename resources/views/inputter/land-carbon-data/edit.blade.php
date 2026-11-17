<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Edit Data Penyerapan Karbon Alam
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui informasi aktivitas penyerapan karbon pada lahan.
            </p>
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
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <form action="{{ route('inputter.land-carbon-data.update', $item->id) }}"
                      method="POST"
                      class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="land_id" class="mb-2 block text-sm font-medium text-gray-700">
                            Pilih Lahan
                        </label>

                        <select id="land_id" name="land_id" required
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                            <option value="">Pilih Lahan</option>
                            @foreach ($lands as $land)
                                <option value="{{ $land->id }}"
                                    {{ old('land_id', $item->land_id) == $land->id ? 'selected' : '' }}>
                                    {{ $land->nama_lahan }}
                                </option>
                            @endforeach
                        </select>

                        @error('land_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_aktivitas" class="mb-2 block text-sm font-medium text-gray-700">
                            Tanggal Aktivitas
                        </label>

                        <input type="date"
                               id="tanggal_aktivitas"
                               name="tanggal_aktivitas"
                               max="{{ now()->format('Y-m-d') }}"
                               value="{{ old('tanggal_aktivitas', $item->tanggal_aktivitas->format('Y-m-d')) }}"
                               required
                               class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                        @error('tanggal_aktivitas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="trees_planted" class="mb-2 block text-sm font-medium text-gray-700">
                                Jumlah Pohon yang Ditanam
                            </label>

                            <input type="number"
                                   id="trees_planted"
                                   name="trees_planted"
                                   min="0"
                                   value="{{ old('trees_planted', $item->trees_planted) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                            @error('trees_planted')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="cover_crop_area" class="mb-2 block text-sm font-medium text-gray-700">
                                Luas Tanaman Penutup Tanah (ha)
                            </label>

                            <input type="number"
                                   id="cover_crop_area"
                                   name="cover_crop_area"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('cover_crop_area', $item->cover_crop_area) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                            @error('cover_crop_area')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tillage_method" class="mb-2 block text-sm font-medium text-gray-700">
                                Metode Pengolahan Tanah
                            </label>

                            <select id="tillage_method" name="tillage_method" required
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                <option value="">Pilih Metode</option>
                                @foreach ($tillageMethods as $method)
                                    <option value="{{ $method }}"
                                        {{ old('tillage_method', $item->tillage_method) == $method ? 'selected' : '' }}>
                                        {{ $tillageLabels[$method] ?? $method }}
                                    </option>
                                @endforeach
                            </select>

                            @error('tillage_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="biochar_amount" class="mb-2 block text-sm font-medium text-gray-700">
                                Jumlah Biochar (kg)
                            </label>

                            <input type="number"
                                   id="biochar_amount"
                                   name="biochar_amount"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('biochar_amount', $item->biochar_amount) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                            @error('biochar_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="compost_amount" class="mb-2 block text-sm font-medium text-gray-700">
                                Jumlah Kompos (kg)
                            </label>

                            <input type="number"
                                   id="compost_amount"
                                   name="compost_amount"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('compost_amount', $item->compost_amount) }}"
                                   required
                                   class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                            @error('compost_amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                            Catatan Tambahan
                        </label>

                        <textarea id="notes"
                                  name="notes"
                                  rows="4"
                                  maxlength="2000"
                                  class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"
                                  placeholder="Tambahkan catatan jika diperlukan">{{ old('notes', $item->notes) }}</textarea>

                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:justify-end">
                        <a href="{{ route('inputter.land-carbon-data.show', $item->id) }}"
                           class="rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Batal
                        </a>

                        <button type="submit"
                                class="rounded-lg bg-green-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
