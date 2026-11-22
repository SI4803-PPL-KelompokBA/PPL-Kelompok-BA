
@csrf

<div class="space-y-8 p-6 sm:p-8">

    {{-- INFORMASI DASAR --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4"/>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">Informasi Dasar</h4>
                <p class="text-xs text-gray-500">Identitas dan lokasi lahan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Nama Lahan --}}
            <div>
                <label for="nama_lahan" class="mb-2 block text-sm font-semibold text-gray-700">
                    Nama Lahan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lahan" id="nama_lahan"
                    value="{{ old('nama_lahan', $land->nama_lahan ?? '') }}"
                    maxlength="255" required
                    placeholder="Contoh: Kebun Utara"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                @error('nama_lahan')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Lokasi --}}
            <div>
                <label for="lokasi" class="mb-2 block text-sm font-semibold text-gray-700">
                    Lokasi Lahan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="lokasi" id="lokasi"
                    value="{{ old('lokasi', $land->lokasi ?? '') }}"
                    maxlength="255" required
                    placeholder="Contoh: Sumedang, Jawa Barat"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                @error('lokasi')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>

    <div class="border-t border-gray-100"></div>

    {{-- LUAS DAN TARGET EMISI --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M3 3h18v18H3zM8 8h3v3H8zM14 8h2M8 14h2M14 14h3M14 17h3"/>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">Luas dan Target Emisi</h4>
                <p class="text-xs text-gray-500">Informasi kapasitas area dan target pengurangan emisi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Luas Area --}}
            <div>
                <label for="luas_area" class="mb-2 block text-sm font-semibold text-gray-700">
                    Luas Area (ha) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" name="luas_area" id="luas_area"
                        value="{{ old('luas_area', $land->luas_area ?? '') }}"
                        min="0.01" step="0.01" required
                        placeholder="Contoh: 150.50"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-14 text-sm text-gray-800 placeholder-gray-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">ha</span>
                </div>
                @error('luas_area')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Target Pengurangan Emisi --}}
            <div>
                <label for="target_pengurangan_emisi" class="mb-2 block text-sm font-semibold text-gray-700">
                    Target Pengurangan Emisi <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" name="target_pengurangan_emisi"
                        id="target_pengurangan_emisi"
                        value="{{ old('target_pengurangan_emisi', $land->target_pengurangan_emisi ?? 0) }}"
                        min="0" max="100" step="0.01" required
                        placeholder="Contoh: 20"
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-800 placeholder-gray-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-gray-400">%</span>
                </div>
                @error('target_pengurangan_emisi')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>
    </section>

    <div class="border-t border-gray-100"></div>

    {{-- KOORDINAT LOKASI --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 21s8-7.5 8-13a8 8 0 10-16 0c0 5.5 8 13 8 13z"/>
                    <circle cx="12" cy="8" r="2.5" stroke-width="1.8"/>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">Koordinat Lokasi</h4>
                <p class="text-xs text-gray-500">Titik geografis lahan (opsional).</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Latitude --}}
            <div>
                <label for="latitude" class="mb-2 block text-sm font-semibold text-gray-700">
                    Latitude <span class="font-normal text-gray-400">(Opsional)</span>
                </label>
                <input type="number" name="latitude" id="latitude"
                    value="{{ old('latitude', $land->latitude ?? '') }}"
                    min="-90" max="90" step="any"
                    placeholder="Contoh: -6.200000"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                @error('latitude')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Longitude --}}
            <div>
                <label for="longitude" class="mb-2 block text-sm font-semibold text-gray-700">
                    Longitude <span class="font-normal text-gray-400">(Opsional)</span>
                </label>
                <input type="number" name="longitude" id="longitude"
                    value="{{ old('longitude', $land->longitude ?? '') }}"
                    min="-180" max="180" step="any"
                    placeholder="Contoh: 106.816666"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                @error('longitude')
                    <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="mt-4 rounded-xl border border-blue-100 bg-blue-50 p-4">
            <div class="flex items-start gap-3">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                </svg>
                <p class="text-xs leading-relaxed text-blue-700">
                    Koordinat digunakan untuk menunjukkan posisi geografis lahan.
                    Kosongkan jika informasi koordinat belum tersedia.
                </p>
            </div>
        </div>
    </section>

    <div class="border-t border-gray-100"></div>

    {{-- STATUS OPERASIONAL --}}
    <section>
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.8" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-gray-900">Status Operasional</h4>
                <p class="text-xs text-gray-500">Tentukan kondisi operasional lahan.</p>
            </div>
        </div>

        <label for="status_operasional" class="mb-2 block text-sm font-semibold text-gray-700">
            Status Lahan <span class="text-red-500">*</span>
        </label>

        <select name="status_operasional" id="status_operasional" required
            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 transition focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
            <option value="aktif"
                {{ old('status_operasional', $land->status_operasional ?? 'aktif') === 'aktif' ? 'selected' : '' }}>
                Aktif
            </option>
            <option value="nonaktif"
                {{ old('status_operasional', $land->status_operasional ?? 'aktif') === 'nonaktif' ? 'selected' : '' }}>
                Nonaktif
            </option>
        </select>

        @error('status_operasional')
            <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
        @enderror
    </section>

</div>

{{-- TOMBOL AKSI --}}
<div class="flex flex-col-reverse gap-3 border-t border-gray-100 bg-gray-50/70 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
    <p class="text-xs text-gray-400">
        Pastikan data yang dimasukkan sudah sesuai.
    </p>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('esg.lands.index') }}"
            class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-100">
            Batal
        </a>

        <button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5 hover:bg-emerald-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ isset($land) ? 'Simpan Perubahan' : 'Simpan Lahan' }}
        </button>
    </div>
</div>
