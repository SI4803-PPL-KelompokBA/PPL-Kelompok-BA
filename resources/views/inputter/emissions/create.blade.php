
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $isEdit ? 'Edit' : 'Input' }} {{ $cfg['label'] }}
            </h2>
            <p class="text-sm text-gray-500">
                Catat aktivitas transportasi hasil panen menuju fasilitas pemrosesan lokal.
            </p>
        </div>
    </x-slot>

    @php
        $detailValues = old(
            'detail',
            $emission?->detail_tambahan ?? []
        );
    @endphp

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <form
            method="POST"
            enctype="multipart/form-data"
            action="{{ $isEdit
                ? route('inputter.emissions.update', $emission)
                : route('inputter.emissions.store', $kategori) }}"
            class="bg-white shadow-sm border border-gray-100 sm:rounded-xl p-6 space-y-6"
            x-data="{
                nilai: 0,
                jumlah: @js(old('jumlah_penggunaan', $emission?->jumlah_penggunaan ?? 0)),
                d: @js($detailValues),
                dari: @js($cfg['jumlah_dari'] ?? null),

                get qty() {
                    return this.dari
                        ? this.dari.reduce(
                            (total, key) => total * (parseFloat(this.d[key]) || 0),
                            1
                        )
                        : (parseFloat(this.jumlah) || 0);
                },

                get total() {
                    return (this.qty * this.nilai).toFixed(4);
                }
            }"
            x-init="$nextTick(() => {
                nilai = parseFloat(
                    $refs.factor.selectedOptions[0]?.dataset.nilai || 0
                );
            })"
        >
            @csrf

            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="border-b border-gray-100 pb-4">
                <h3 class="font-semibold text-gray-800">
                    Informasi Aktivitas Transportasi
                </h3>
                <p class="text-sm text-gray-500 mt-1">
                    Masukkan informasi asal perkebunan dan tujuan pengiriman hasil panen.
                </p>
            </div>

            <div>
                <x-input-label for="land_id" value="Lahan Perkebunan" />

                <select
                    id="land_id"
                    name="land_id"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Pilih lahan perkebunan</option>

                    @foreach ($lands as $land)
                        <option
                            value="{{ $land->id }}"
                            @selected(old('land_id', $emission?->land_id) == $land->id)
                        >
                            {{ $land->nama_lahan }}
                        </option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('land_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label
                    for="emission_factor_id"
                    :value="$cfg['faktor_label'] ?? 'Jenis kendaraan'"
                />

                <select
                    id="emission_factor_id"
                    name="emission_factor_id"
                    x-ref="factor"
                    required
                    x-on:change="nilai = parseFloat($event.target.selectedOptions[0].dataset.nilai || 0)"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option value="">Pilih jenis kendaraan</option>

                    @foreach ($factors as $factor)
                        <option
                            value="{{ $factor->id }}"
                            data-nilai="{{ $factor->nilai_emisi }}"
                            @selected(old('emission_factor_id', $emission?->emission_factor_id) == $factor->id)
                        >
                            {{ $factor->nama_faktor }}
                            ({{ $factor->nilai_emisi }} {{ $factor->satuan }})
                        </option>
                    @endforeach
                </select>

                <x-input-error :messages="$errors->get('emission_factor_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tanggal_aktivitas" value="Tanggal Aktivitas" />

                <x-text-input
                    id="tanggal_aktivitas"
                    name="tanggal_aktivitas"
                    type="date"
                    class="mt-1 block w-full"
                    :value="old(
                        'tanggal_aktivitas',
                        $emission?->tanggal_aktivitas?->format('Y-m-d') ?? now()->toDateString()
                    )"
                    required
                />

                <x-input-error :messages="$errors->get('tanggal_aktivitas')" class="mt-2" />
            </div>

            @unless (isset($cfg['jumlah_dari']))
                <div>
                    <x-input-label
                        for="jumlah_penggunaan"
                        :value="$cfg['jumlah_label']"
                    />

                    <x-text-input
                        id="jumlah_penggunaan"
                        name="jumlah_penggunaan"
                        type="number"
                        step="any"
                        min="0.0001"
                        class="mt-1 block w-full"
                        x-model="jumlah"
                        :value="old('jumlah_penggunaan', $emission?->jumlah_penggunaan)"
                        required
                    />

                    <x-input-error :messages="$errors->get('jumlah_penggunaan')" class="mt-2" />
                </div>
            @endunless

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($cfg['extra'] as $field)
                    <div class="{{ $field['name'] === 'tujuan' ? 'md:col-span-2' : '' }}">
                        <x-input-label
                            :for="'d_'.$field['name']"
                            :value="$field['label']"
                        />

                        @if ($field['type'] === 'select')
                            <select
                                id="d_{{ $field['name'] }}"
                                name="detail[{{ $field['name'] }}]"
                                x-model="d['{{ $field['name'] }}']"
                                @required($field['required'])
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm"
                            >
                                <option value="">Pilih</option>

                                @foreach ($field['options'] as $option)
                                    <option
                                        value="{{ $option }}"
                                        @selected(($detailValues[$field['name']] ?? '') === $option)
                                    >
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <x-text-input
                                :id="'d_'.$field['name']"
                                :name="'detail['.$field['name'].']'"
                                :type="$field['type']"
                                :step="$field['type'] === 'number' ? 'any' : null"
                                :min="$field['type'] === 'number' ? '0.0001' : null"
                                class="mt-1 block w-full"
                                x-model="d['{{ $field['name'] }}']"
                                :value="$detailValues[$field['name']] ?? ''"
                                :required="$field['required']"
                            />
                        @endif

                        <x-input-error
                            :messages="$errors->get('detail.'.$field['name'])"
                            class="mt-2"
                        />
                    </div>
                @endforeach
            </div>

            <div>
                <x-input-label
                    for="bukti_dokumen"
                    value="Bukti Dokumen (Opsional)"
                />

                <input
                    id="bukti_dokumen"
                    name="bukti_dokumen"
                    type="file"
                    accept=".pdf,.jpg,.jpeg,.png"
                    class="mt-2 block w-full text-sm text-gray-600 border border-gray-300 rounded-lg p-2"
                />

                <p class="text-xs text-gray-500 mt-1">
                    Format PDF, JPG, JPEG, atau PNG. Maksimal 2 MB.
                </p>

                @if ($isEdit && $emission->bukti_dokumen)
                    <p class="text-sm mt-2">
                        Bukti saat ini:
                        <a
                            href="{{ Storage::url($emission->bukti_dokumen) }}"
                            target="_blank"
                            class="text-indigo-600 underline"
                        >
                            Lihat dokumen
                        </a>
                    </p>
                @endif

                <x-input-error :messages="$errors->get('bukti_dokumen')" class="mt-2" />
            </div>

            <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-5 space-y-3">
                <h3 class="font-semibold text-indigo-900">
                    Estimasi Perhitungan Emisi
                </h3>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total penggunaan</span>
                    <span class="font-semibold" x-text="qty.toFixed(4)"></span>
                </div>

                @if ($kategori === 'transportasi_lokal')
                    <p class="text-xs text-gray-600">
                        Total penggunaan = Jarak tempuh (km) × Muatan (ton).
                        Satuan hasil: ton-km.
                    </p>
                @endif

                <div class="border-t border-indigo-200 pt-3 flex justify-between items-center">
                    <span class="font-semibold text-indigo-900">
                        Estimasi Emisi
                    </span>

                    <span class="text-xl font-bold text-indigo-700">
                        <span x-text="total"></span>
                        <span class="text-sm">kg CO₂e</span>
                    </span>
                </div>

                <p class="text-xs text-gray-500">
                    Hasil akhir akan dihitung ulang oleh sistem berdasarkan faktor emisi yang dipilih.
                </p>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-3 border-t border-gray-100">
                <a
                    href="{{ route('inputter.emissions.index') }}"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex justify-center items-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
                >
                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Data Transportasi' }}
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
