
<x-app-layout>
    @php
        $withProgress = $lands->filter(
            fn ($land) => $land->progress_percentage !== null
        );

        $achievedCount = $withProgress->filter(
            fn ($land) => $land->progress_percentage >= 100
        )->count();

        $inProgressCount = $withProgress->filter(
            fn ($land) => $land->progress_percentage > 0
                && $land->progress_percentage < 100
        )->count();

        $notAchievedCount = $withProgress->filter(
            fn ($land) => $land->progress_percentage <= 0
        )->count();

        $incompleteCount = $lands->count() - $withProgress->count();

        $avgProgress = $withProgress->isNotEmpty()
            ? $withProgress->avg('progress_percentage')
            : 0;

        $totalTargets = $lands->filter(
            fn ($land) => $land->target_pengurangan_emisi !== null
        )->count();
    @endphp

    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-7">

            {{-- HEADER --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-emerald-600">
                        ESG Management / Performance
                    </p>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        Target Pengurangan Emisi
                    </h1>

                    <p class="mt-2 max-w-2xl text-sm text-slate-500">
                        Kelola target, simulasikan pengurangan, pantau pencapaian,
                        dan evaluasi performa emisi setiap lahan.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('esg.targets.index') }}"
                      class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                    <div>
                        <label for="year" class="block text-xs text-slate-500">
                            Tahun monitoring
                        </label>

                        <select name="year"
                                id="year"
                                onchange="this.form.submit()"
                                class="mt-1 border-0 bg-transparent p-0 pr-7 text-sm font-bold text-slate-800 focus:ring-0">
                            @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                <option value="{{ $y }}" @selected((int) $year === $y)>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>

            {{-- FLASH MESSAGE --}}
            @if (session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('general'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-5 py-3 text-sm text-red-700">
                    {{ $errors->first('general') }}
                </div>
            @endif

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Total Lahan</p>
                    <p class="mt-3 text-3xl font-bold text-slate-900">
                        {{ $lands->count() }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Lahan terdaftar</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Target Tersimpan</p>
                    <p class="mt-3 text-3xl font-bold text-blue-600">
                        {{ $totalTargets }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Lahan dengan target aktif</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Rata-rata Progres</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">
                        {{ number_format($avgProgress, 1) }}%
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Pencapaian terhadap target</p>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Target Tercapai</p>
                    <p class="mt-3 text-3xl font-bold text-emerald-600">
                        {{ $achievedCount }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Lahan mencapai target</p>
                </div>

            </div>

            {{-- STATUS SUMMARY --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Ringkasan Pencapaian
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Status monitoring seluruh lahan pada tahun {{ $year }}.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-4">
                    @foreach ([
                        ['label' => 'Tercapai', 'count' => $achievedCount, 'color' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                        ['label' => 'Dalam progres', 'count' => $inProgressCount, 'color' => 'bg-amber-400', 'text' => 'text-amber-700'],
                        ['label' => 'Belum tercapai', 'count' => $notAchievedCount, 'color' => 'bg-red-400', 'text' => 'text-red-700'],
                        ['label' => 'Data belum lengkap', 'count' => $incompleteCount, 'color' => 'bg-slate-400', 'text' => 'text-slate-700'],
                    ] as $item)
                        <div>
                            <div class="mb-2 flex justify-between text-sm">
                                <span class="text-slate-600">{{ $item['label'] }}</span>
                                <strong class="{{ $item['text'] }}">{{ $item['count'] }}</strong>
                            </div>

                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $item['color'] }}"
                                     style="width: {{ $lands->count() ? ($item['count'] / $lands->count()) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- SIMULATOR --}}
            <div class="overflow-hidden rounded-2xl border border-emerald-100 bg-white shadow-sm">
                <div class="border-b border-emerald-100 bg-emerald-50/60 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-900">
                        Simulasi Target Emisi
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Coba berbagai persentase target untuk mengetahui batas maksimal emisi.
                        Simulasi tidak mengubah target tersimpan.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2">
                    <div class="space-y-4">
                        <div>
                            <label for="simulation-land"
                                   class="mb-2 block text-sm font-semibold text-slate-700">
                                Pilih lahan
                            </label>

                            <select id="simulation-land"
                                    class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="">Pilih lahan...</option>

                                @foreach ($lands as $land)
                                    <option value="{{ $land->id }}"
                                            data-baseline="{{ $land->baseline_emission ?? '' }}"
                                            data-current="{{ $land->current_emission ?? '' }}"
                                            data-name="{{ $land->nama_lahan }}"
                                            data-target="{{ $land->target_pengurangan_emisi ?? '' }}">
                                        {{ $land->nama_lahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="simulation-target"
                                   class="mb-2 block text-sm font-semibold text-slate-700">
                                Target pengurangan emisi (%)
                            </label>

                            <div class="relative">
                                <input type="number"
                                       id="simulation-target"
                                       min="0.01"
                                       max="100"
                                       step="0.01"
                                       placeholder="Contoh: 25"
                                       class="w-full rounded-xl border-slate-300 py-3 pr-10 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-slate-400">
                                    %
                                </span>
                            </div>
                        </div>

                        <button type="button"
                                id="calculate-simulation"
                                class="w-full rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            Hitung Simulasi
                        </button>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-5">
                        <p class="text-sm font-semibold text-slate-700">
                            Hasil Simulasi
                        </p>

                        <p id="simulation-message" class="mt-2 text-sm text-slate-500">
                            Pilih lahan dan masukkan target untuk melihat hasil simulasi.
                        </p>

                        <div class="mt-5 rounded-xl border border-emerald-100 bg-white p-4">
                            <p class="text-xs text-slate-500">Batas maksimal emisi</p>
                            <p id="simulation-result" class="mt-2 text-3xl font-bold text-emerald-700">
                                - kg CO₂e
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                Berdasarkan baseline dan target yang dipilih.
                            </p>
                        </div>

                        <div class="mt-4 rounded-xl bg-white p-4">
                            <p class="text-xs text-slate-500">Emisi aktual saat ini</p>
                            <p id="simulation-current" class="mt-1 text-lg font-bold text-slate-800">
                                - kg CO₂e
                            </p>
                            <p id="simulation-gap" class="mt-2 text-sm text-slate-500">
                                Hasil simulasi akan ditampilkan di sini.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MONITORING --}}
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-6">
                    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-center">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">
                                Monitoring dan Pengelolaan Target
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Buat, ubah, dan hapus target serta pantau hasil setiap lahan.
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 sm:flex-row">
                            <input type="search"
                                   id="land-search"
                                   placeholder="Cari nama lahan..."
                                   class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                            <select id="status-filter"
                                    class="rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <option value="all">Semua status</option>
                                <option value="achieved">Tercapai</option>
                                <option value="progress">Dalam progres</option>
                                <option value="not-achieved">Belum tercapai</option>
                                <option value="incomplete">Belum ada data</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 xl:grid-cols-2" id="land-list">
                    @forelse ($lands as $land)
                        @php
                            $progress = $land->progress_percentage;
                            $reduction = $land->reduction_percentage;
                            $target = $land->target_pengurangan_emisi;
                            $baseline = $land->baseline_emission;

                            if ($target === null) {
                                $status = 'incomplete';
                                $statusLabel = 'Target belum ditetapkan';
                                $statusClass = 'bg-slate-100 text-slate-600';
                                $barClass = 'bg-slate-300';
                            } elseif ($progress === null) {
                                $status = 'incomplete';
                                $statusLabel = 'Data emisi belum lengkap';
                                $statusClass = 'bg-slate-100 text-slate-600';
                                $barClass = 'bg-slate-300';
                            } elseif ($progress >= 100) {
                                $status = 'achieved';
                                $statusLabel = 'Target tercapai';
                                $statusClass = 'bg-emerald-100 text-emerald-700';
                                $barClass = 'bg-emerald-500';
                            } elseif ($progress > 0) {
                                $status = 'progress';
                                $statusLabel = 'Dalam progres';
                                $statusClass = 'bg-amber-100 text-amber-700';
                                $barClass = 'bg-amber-400';
                            } else {
                                $status = 'not-achieved';
                                $statusLabel = 'Belum tercapai';
                                $statusClass = 'bg-red-100 text-red-700';
                                $barClass = 'bg-red-400';
                            }

                            $targetValue = (float) ($target ?? 0);
                            $baselineValue = (float) ($baseline ?? 0);
                            $limit = $baselineValue > 0
                                ? $baselineValue * (1 - ($targetValue / 100))
                                : null;

                            $gap = $reduction !== null
                                ? $targetValue - $reduction
                                : null;

                            $errorBag = $errors->getBag('target_' . $land->id);
                        @endphp

                        <article class="land-card rounded-2xl border border-slate-200 p-5 transition hover:border-emerald-200 hover:shadow-md"
                                 data-name="{{ strtolower($land->nama_lahan) }}"
                                 data-status="{{ $status }}">

                            {{-- LAND HEADER --}}
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 font-bold text-emerald-700">
                                        {{ strtoupper(substr($land->nama_lahan, 0, 1)) }}
                                    </div>

                                    <div>
                                        <h3 class="font-bold text-slate-900">
                                            {{ $land->nama_lahan }}
                                        </h3>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $land->lokasi ?? 'Lokasi tidak tersedia' }}
                                        </p>
                                    </div>
                                </div>

                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            {{-- EMISSION DATA --}}
                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">
                                        Baseline {{ $previousYear }}
                                    </p>
                                    <p class="mt-1 font-bold text-slate-800">
                                        {{ $baseline !== null ? number_format((float) $baseline, 2) : '-' }}
                                    </p>
                                    <p class="text-xs text-slate-400">kg CO₂e</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">
                                        Emisi aktual {{ $year }}
                                    </p>
                                    <p class="mt-1 font-bold text-slate-800">
                                        {{ $land->current_emission !== null
                                            ? number_format((float) $land->current_emission, 2)
                                            : '-' }}
                                    </p>
                                    <p class="text-xs text-slate-400">kg CO₂e</p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Reduksi aktual</p>
                                    <p class="mt-1 font-bold {{ $reduction !== null && $reduction >= 0 ? 'text-emerald-700' : 'text-red-600' }}">
                                        {{ $reduction !== null ? number_format($reduction, 2) . '%' : '-' }}
                                    </p>
                                </div>

                                <div class="rounded-xl bg-slate-50 p-3">
                                    <p class="text-xs text-slate-500">Batas emisi target</p>
                                    <p class="mt-1 font-bold text-slate-800">
                                        {{ $limit !== null ? number_format($limit, 2) : '-' }}
                                    </p>
                                    <p class="text-xs text-slate-400">kg CO₂e</p>
                                </div>
                            </div>

                            {{-- PROGRESS --}}
                            <div class="mt-5">
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-slate-600">
                                        Progres target
                                    </span>
                                    <strong class="text-slate-900">
                                        {{ $progress !== null ? number_format($progress, 1) . '%' : '-' }}
                                    </strong>
                                </div>

                                <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $barClass }}"
                                         style="width: {{ $progress !== null ? max(0, min(100, $progress)) : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            {{-- EVALUATION --}}
                            <div class="mt-4 rounded-xl p-4
                                {{ $status === 'achieved'
                                    ? 'bg-emerald-50'
                                    : ($status === 'not-achieved'
                                        ? 'bg-red-50'
                                        : ($status === 'progress' ? 'bg-amber-50' : 'bg-slate-50')) }}">

                                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                    Analisis pencapaian
                                </p>

                                @if ($target === null)
                                    <p class="mt-2 text-sm text-slate-700">
                                        Target belum ditetapkan. Silakan buat target pengurangan emisi untuk lahan ini.
                                    </p>
                                @elseif ($progress === null)
                                    <p class="mt-2 text-sm text-slate-700">
                                        Data emisi belum lengkap. Sistem membutuhkan data baseline dan emisi tahun monitoring untuk menghitung progres.
                                    </p>
                                @elseif ($status === 'achieved')
                                    <p class="mt-2 text-sm text-emerald-800">
                                        Target berhasil dicapai. Pertahankan pengelolaan emisi agar pencapaian tetap konsisten.
                                    </p>
                                @elseif ($status === 'progress')
                                    <p class="mt-2 text-sm text-amber-800">
                                        Pengurangan aktual masih kurang
                                        {{ number_format(max(0, $gap), 2) }}
                                        poin persentase dari target. Perlu evaluasi aktivitas penyumbang emisi.
                                    </p>
                                @else
                                    <p class="mt-2 text-sm text-red-700">
                                        Pengurangan emisi belum mencapai target. Evaluasi sumber emisi dan rencanakan tindakan perbaikan.
                                    </p>
                                @endif
                            </div>

                            {{-- CREATE / UPDATE FORM --}}
                            <div class="mt-5 border-t border-slate-100 pt-5">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-sm font-bold text-slate-800">
                                        {{ $target === null ? 'Buat Target Baru' : 'Ubah Target' }}
                                    </h4>

                                    @if ($target !== null)
                                        <span class="rounded-lg bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            Target tersimpan: {{ number_format((float) $target, 2) }}%
                                        </span>
                                    @endif
                                </div>

                                <form method="POST"
                                      action="{{ route('esg.targets.update', $land->id) }}"
                                      class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-end">

                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="year" value="{{ $year }}">

                                    <div class="flex-1">
                                        <label for="target-{{ $land->id }}"
                                               class="mb-2 block text-xs font-medium text-slate-600">
                                            Target pengurangan emisi (%)
                                        </label>

                                        <input type="number"
                                               id="target-{{ $land->id }}"
                                               name="target_pengurangan_emisi"
                                               min="0.01"
                                               max="100"
                                               step="0.01"
                                               required
                                               value="{{ old('target_pengurangan_emisi', $target) }}"
                                               placeholder="Contoh: 25"
                                               class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                                        @if ($errorBag->has('target_pengurangan_emisi'))
                                            <p class="mt-1 text-xs text-red-600">
                                                {{ $errorBag->first('target_pengurangan_emisi') }}
                                            </p>
                                        @endif
                                    </div>

                                    <button type="submit"
                                            class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        {{ $target === null ? 'Buat Target' : 'Simpan Perubahan' }}
                                    </button>
                                </form>

                                {{-- DELETE FORM --}}
                                @if ($target !== null)
                                    <form method="POST"
                                          action="{{ route('esg.targets.destroy', $land->id) }}"
                                          onsubmit="return confirm('Yakin ingin menghapus target pengurangan emisi {{ $land->nama_lahan }}?')"
                                          class="mt-3">

                                        @csrf
                                        @method('DELETE')

                                        <input type="hidden" name="year" value="{{ $year }}">

                                        <button type="submit"
                                                class="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                            Hapus Target
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </article>

                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 p-10 text-center xl:col-span-2">
                            <p class="font-semibold text-slate-700">
                                Belum ada data lahan
                            </p>
                            <p class="mt-1 text-sm text-slate-500">
                                Data lahan akan muncul setelah tersedia.
                            </p>
                        </div>
                    @endforelse
                </div>

                <p id="empty-filter"
                   class="hidden px-6 pb-6 text-center text-sm text-slate-500">
                    Tidak ada lahan yang sesuai dengan pencarian atau filter.
                </p>
            </div>

            {{-- INFORMATION --}}
            <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                <strong>Informasi:</strong>
                Target dapat dibuat, diubah, dan dihapus oleh ESG Officer.
                Perhitungan reduksi dan progres dilakukan otomatis berdasarkan data emisi
                yang dicatat Inputter. Simulasi tidak mengubah target yang tersimpan.
            </div>

        </div>
    </div>

    {{-- JAVASCRIPT: SIMULATOR + FILTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const landSelect = document.getElementById('simulation-land');
            const targetInput = document.getElementById('simulation-target');
            const calculateButton = document.getElementById('calculate-simulation');

            const result = document.getElementById('simulation-result');
            const currentText = document.getElementById('simulation-current');
            const message = document.getElementById('simulation-message');
            const gapText = document.getElementById('simulation-gap');

            function resetSimulation() {
                result.textContent = '- kg CO₂e';
                currentText.textContent = '- kg CO₂e';
                gapText.textContent = 'Hasil simulasi akan ditampilkan di sini.';
                gapText.className = 'mt-2 text-sm text-slate-500';
            }

            // Isi target simulasi dari target yang sudah tersimpan.
            landSelect.addEventListener('change', function () {
                const option = landSelect.options[landSelect.selectedIndex];

                if (!landSelect.value) {
                    targetInput.value = '';
                    message.textContent = 'Pilih lahan untuk memulai simulasi.';
                    resetSimulation();
                    return;
                }

                targetInput.value = option.dataset.target || '';
                message.textContent = 'Masukkan target simulasi untuk ' + option.dataset.name + '.';
                resetSimulation();
            });

            calculateButton.addEventListener('click', function () {
                const option = landSelect.options[landSelect.selectedIndex];
                const target = Number(targetInput.value);

                if (!landSelect.value) {
                    message.textContent = 'Silakan pilih lahan terlebih dahulu.';
                    return;
                }

                if (
                    targetInput.value === '' ||
                    !Number.isFinite(target) ||
                    target <= 0 ||
                    target > 100
                ) {
                    message.textContent = 'Target harus lebih dari 0% dan maksimal 100%.';
                    resetSimulation();
                    return;
                }

                const baseline = Number(option.dataset.baseline);
                const currentRaw = option.dataset.current;

                if (!option.dataset.baseline || baseline <= 0) {
                    message.textContent = 'Data baseline emisi belum tersedia. Simulasi belum dapat dilakukan.';
                    resetSimulation();
                    return;
                }

                const limit = baseline * (1 - target / 100);

                result.textContent = limit.toFixed(2) + ' kg CO₂e';
                message.textContent =
                    'Hasil simulasi ' + option.dataset.name +
                    ' dengan target pengurangan ' + target + '%.';

                if (currentRaw === '') {
                    currentText.textContent = 'Belum tersedia';
                    gapText.textContent = 'Batas maksimal emisi sudah dihitung. Data emisi aktual belum tersedia untuk dibandingkan.';
                    gapText.className = 'mt-2 text-sm text-slate-500';
                    return;
                }

                const current = Number(currentRaw);
                const difference = current - limit;

                currentText.textContent = current.toFixed(2) + ' kg CO₂e';

                if (difference <= 0) {
                    gapText.textContent =
                        'Emisi aktual berada di bawah batas target sebesar ' +
                        Math.abs(difference).toFixed(2) +
                        ' kg CO₂e. Target simulasi terpenuhi.';

                    gapText.className = 'mt-2 text-sm font-medium text-emerald-700';
                } else {
                    gapText.textContent =
                        'Emisi aktual melebihi batas target sebesar ' +
                        difference.toFixed(2) +
                        ' kg CO₂e.';

                    gapText.className = 'mt-2 text-sm font-medium text-red-600';
                }
            });

            // Pencarian dan filter lahan.
            const search = document.getElementById('land-search');
            const filter = document.getElementById('status-filter');
            const cards = document.querySelectorAll('.land-card');
            const empty = document.getElementById('empty-filter');

            function filterLands() {
                const keyword = search.value.toLowerCase().trim();
                const selectedStatus = filter.value;

                let visible = 0;

                cards.forEach(function (card) {
                    const matchesName = card.dataset.name.includes(keyword);
                    const matchesStatus =
                        selectedStatus === 'all' ||
                        card.dataset.status === selectedStatus;

                    const show = matchesName && matchesStatus;

                    card.classList.toggle('hidden', !show);

                    if (show) visible++;
                });

                empty.classList.toggle('hidden', visible > 0);
            }

            search.addEventListener('input', filterLands);
            filter.addEventListener('change', filterLands);
        });
    </script>
</x-app-layout>
