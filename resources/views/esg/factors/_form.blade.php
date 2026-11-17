@csrf

<div x-data="{ kategori: @js(old('kategori', $factor->kategori)) }" class="space-y-5">
    <div>
        <x-input-label for="kategori" value="Kategori" />
        <select id="kategori" name="kategori" x-model="kategori" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">— Pilih kategori —</option>
            @foreach ($kategoriList as $k)
                <option value="{{ $k }}">{{ $k }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nama_faktor" value="Nama faktor" />
        <x-text-input id="nama_faktor" name="nama_faktor" type="text" class="mt-1 block w-full"
                      :value="old('nama_faktor', $factor->nama_faktor)" />
        <x-input-error :messages="$errors->get('nama_faktor')" class="mt-2" />
    </div>

    <div x-show="kategori !== @js($penyerapan)">
        <x-input-label for="scope" value="Scope" />
        <select id="scope" name="scope" x-bind:disabled="kategori === @js($penyerapan)"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            <option value="">— Pilih scope —</option>
            @foreach ([1, 2, 3] as $s)
                <option value="{{ $s }}" @selected((string) old('scope', $factor->scope) === (string) $s)>Scope {{ $s }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('scope')" class="mt-2" />
    </div>
    <p x-show="kategori === @js($penyerapan)" class="text-sm text-gray-500">
        Penyerapan karbon tidak memiliki scope.
    </p>

    <div>
        <x-input-label for="nilai_emisi" value="Nilai emisi" />
        <x-text-input id="nilai_emisi" name="nilai_emisi" type="number" step="any" class="mt-1 block w-full"
                      :value="old('nilai_emisi', $factor->nilai_emisi)" />
        <p class="mt-1 text-xs text-gray-500" x-show="kategori === @js($penyerapan)">Isi dengan angka negatif atau 0.</p>
        <p class="mt-1 text-xs text-gray-500" x-show="kategori !== @js($penyerapan)">Tidak boleh negatif.</p>
        <x-input-error :messages="$errors->get('nilai_emisi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="satuan" value="Satuan" />
        <x-text-input id="satuan" name="satuan" type="text" class="mt-1 block w-full"
                      placeholder="contoh: kg CO2e/liter"
                      :value="old('satuan', $factor->satuan)" />
        <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('esg.emission-factors.index') }}" class="text-sm text-gray-600">Batal</a>
        <x-primary-button>Simpan</x-primary-button>
    </div>
</div>