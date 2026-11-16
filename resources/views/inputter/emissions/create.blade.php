<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input {{ $cfg['label'] }}</h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <form method="POST" enctype="multipart/form-data"
              action="{{ route('inputter.emissions.store', $kategori) }}"
              class="bg-white shadow sm:rounded-lg p-6 space-y-5"
              x-data="{
                  nilai: 0, jumlah: 0, d: {},
                  dari: @js($cfg['jumlah_dari'] ?? null),
                  get qty() { return this.dari ? this.dari.reduce((c, k) => c * (parseFloat(this.d[k]) || 0), 1) : (parseFloat(this.jumlah) || 0) },
                  get total() { return (this.qty * this.nilai).toFixed(4) }
              }">
            @csrf

            <div>
                <x-input-label for="land_id" value="Lahan" />
                <select id="land_id" name="land_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">- pilih lahan -</option>
                    @foreach ($lands as $land)
                        <option value="{{ $land->id }}" @selected(old('land_id') == $land->id)>{{ $land->nama_lahan }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('land_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="emission_factor_id" :value="$cfg['faktor_label'] ?? 'Faktor emisi'" />
                <select id="emission_factor_id" name="emission_factor_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                        x-on:change="nilai = parseFloat($event.target.selectedOptions[0].dataset.nilai || 0)">
                    <option value="">- pilih -</option>
                    @foreach ($factors as $f)
                        <option value="{{ $f->id }}" data-nilai="{{ $f->nilai_emisi }}"
                                @selected(old('emission_factor_id') == $f->id)>
                            {{ $f->nama_faktor }} ({{ $f->satuan }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('emission_factor_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="tanggal_aktivitas" value="Tanggal aktivitas" />
                <x-text-input id="tanggal_aktivitas" name="tanggal_aktivitas" type="date"
                              class="mt-1 block w-full" :value="old('tanggal_aktivitas', now()->toDateString())" required />
                <x-input-error :messages="$errors->get('tanggal_aktivitas')" class="mt-2" />
            </div>

            @unless (isset($cfg['jumlah_dari']))
                <div>
                    <x-input-label for="jumlah_penggunaan" :value="$cfg['jumlah_label']" />
                    <x-text-input id="jumlah_penggunaan" name="jumlah_penggunaan" type="number" step="any"
                                  class="mt-1 block w-full" x-model="jumlah" :value="old('jumlah_penggunaan')" required />
                    <x-input-error :messages="$errors->get('jumlah_penggunaan')" class="mt-2" />
                </div>
            @endunless

            @foreach ($cfg['extra'] as $f)
                <div>
                    <x-input-label :for="'d_'.$f['name']" :value="$f['label']" />
                    @if ($f['type'] === 'select')
                        <select id="d_{{ $f['name'] }}" name="detail[{{ $f['name'] }}]"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">- pilih -</option>
                            @foreach ($f['options'] as $opt)
                                <option value="{{ $opt }}" @selected(old('detail.'.$f['name']) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    @else
                        <x-text-input :id="'d_'.$f['name']" :name="'detail['.$f['name'].']'"
                                      :type="$f['type']" step="any" class="mt-1 block w-full"
                                      x-model="d['{{ $f['name'] }}']"
                                      :value="old('detail.'.$f['name'])" :required="$f['required']" />
                    @endif
                    <x-input-error :messages="$errors->get('detail.'.$f['name'])" class="mt-2" />
                </div>
            @endforeach

            <div>
                <x-input-label for="bukti_dokumen" value="Bukti dokumen (PDF/JPG/PNG, maks 2 MB)" />
                <input id="bukti_dokumen" name="bukti_dokumen" type="file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm" />
                <x-input-error :messages="$errors->get('bukti_dokumen')" class="mt-2" />
            </div>

            <div class="rounded-md bg-gray-50 p-4 text-sm">
                Estimasi emisi: <span class="font-semibold" x-text="total"></span> kg CO₂e
                <span class="text-gray-500">(hasil akhir dihitung ulang di server)</span>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('inputter.emissions.index') }}" class="px-4 py-2 text-gray-600">Batal</a>
                <x-primary-button>Simpan</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>