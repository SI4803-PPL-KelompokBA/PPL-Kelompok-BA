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

        $totalLands = $lands->count();
        $targetCoveragePct = $totalLands > 0 ? round(($totalTargets / $totalLands) * 100) : 0;
    @endphp

    <div class="min-h-screen bg-slate-50/70 py-8 text-slate-800 antialiased">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 1. BREADCRUMB & HEADER SECTION --}}
            <header class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 via-slate-900 to-slate-950 p-6 sm:p-8 text-white shadow-xl shadow-emerald-950/10">
                {{-- Decorative background glow --}}
                <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full bg-emerald-500/15 blur-3xl"></div>
                <div class="pointer-events-none absolute right-1/3 -bottom-24 h-64 w-64 rounded-full bg-teal-400/10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="space-y-3 max-w-3xl">
                        {{-- Breadcrumb Badge --}}
                        <div class="inline-flex items-center gap-2 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-semibold tracking-wide text-emerald-300 backdrop-blur-md">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>ESG Management</span>
                            <span class="text-emerald-500">•</span>
                            <span>Target Pengurangan Emisi</span>
                        </div>

                        {{-- Page Heading --}}
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-white">
                            Monitoring Target Emisi Lahan
                        </h1>

                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                            Pantau capaian dekarbonisasi setiap lahan, tetapkan batas ambisi iklim tahunan, dan simulasikan dampak reduksi emisi secara presisi.
                        </p>
                    </div>

                    {{-- Monitoring Year Selector Card --}}
                    <div class="relative shrink-0">
                        <form method="GET" action="{{ route('esg.targets.index') }}" class="group relative flex items-center gap-3 rounded-2xl border border-white/10 bg-white/10 p-2.5 sm:p-3 shadow-lg backdrop-blur-md transition-all hover:bg-white/15">
                            <div class="flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-xl bg-emerald-500/20 text-emerald-300 ring-1 ring-white/10">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="pr-2">
                                <label for="year" class="block text-[10px] font-semibold uppercase tracking-wider text-slate-300">
                                    Tahun Monitoring
                                </label>
                                <div class="relative mt-0.5">
                                    <select name="year" id="year" onchange="this.form.submit()" class="cursor-pointer appearance-none border-0 bg-transparent py-0 pl-0 pr-7 text-base sm:text-lg font-bold text-white focus:outline-none focus:ring-0">
                                        @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                                            <option value="{{ $y }}" @selected((int) $year === $y) class="bg-slate-900 text-white font-medium">
                                                Tahun {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                    <svg class="pointer-events-none absolute right-0 top-1/2 -translate-y-1/2 h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Quick Meta Strip --}}
                <div class="relative z-10 mt-6 pt-5 border-t border-white/10 flex flex-wrap items-center justify-between gap-4 text-xs text-slate-300 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Baseline komparasi: <strong class="text-white">Tahun {{ $previousYear }}</strong></span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 text-slate-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span> Cakupan Target: <strong class="text-white">{{ $targetCoveragePct }}% Lahan</strong>
                        </span>
                    </div>
                </div>
            </header>

            {{-- 2. FLASH NOTIFICATIONS --}}
            @if (session('success'))
                <div class="flex items-center justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/90 p-4 text-emerald-900 shadow-sm backdrop-blur-sm transition animate-in fade-in slide-in-from-top-2">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-900">Operasi Berhasil</p>
                            <p class="text-xs text-emerald-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="rounded-lg p-1.5 text-emerald-600 hover:bg-emerald-100/60 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if ($errors->has('general'))
                <div class="flex items-center justify-between gap-3 rounded-2xl border border-rose-200 bg-rose-50/90 p-4 text-rose-900 shadow-sm backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white shadow-sm">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-rose-900">Perhatian</p>
                            <p class="text-xs text-rose-700">{{ $errors->first('general') }}</p>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="rounded-lg p-1.5 text-rose-600 hover:bg-rose-100/60 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- 3. SUMMARY STATS KPI CARDS --}}
            <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Total Lahan --}}
                <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-slate-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Lahan</p>
                            <h3 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $totalLands }}</h3>
                            <p class="mt-1 text-xs text-slate-500 font-medium">Lahan terdaftar di sistem</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-700">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                        <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span>Aktif dipantau periode {{ $year }}</span>
                    </div>
                </div>

                {{-- Target Ditetapkan --}}
                <div class="relative overflow-hidden rounded-2xl border border-blue-100 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-blue-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Target Ditetapkan</p>
                            <h3 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $totalTargets }}</h3>
                            <p class="mt-1 text-xs text-slate-500 font-medium">Dari {{ $totalLands }} lahan keseluruhan</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                            <span>Kelengkapan</span>
                            <span class="text-blue-600">{{ $targetCoveragePct }}%</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-blue-600 transition-all duration-500" style="width: {{ $targetCoveragePct }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Rata-rata Progres --}}
                <div class="relative overflow-hidden rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-emerald-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Rata-rata Progres</p>
                            <h3 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ number_format($avgProgress, 1) }}<span class="text-xl font-bold text-emerald-600">%</span></h3>
                            <p class="mt-1 text-xs text-slate-500 font-medium">Realisasi terhadap target</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-xs font-semibold text-slate-600 mb-1">
                            <span>Efisiensi Emisi</span>
                            <span class="text-emerald-600">{{ number_format(min(100, $avgProgress), 1) }}%</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full rounded-full bg-emerald-500 transition-all duration-500" style="width: {{ min(100, $avgProgress) }}%"></div>
                        </div>
                    </div>
                </div>

                {{-- Target Tercapai --}}
                <div class="relative overflow-hidden rounded-2xl border border-amber-100 bg-white p-5 shadow-sm transition hover:shadow-md hover:border-amber-200">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-amber-700">Target Tercapai</p>
                            <h3 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-900">{{ $achievedCount }}</h3>
                            <p class="mt-1 text-xs text-slate-500 font-medium">Lahan melampaui target (≥100%)</p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between text-xs text-slate-500 font-medium">
                        <span>Dalam progres: <strong class="text-slate-800">{{ $inProgressCount }}</strong></span>
                        <span>Belum tercapai: <strong class="text-slate-800">{{ $notAchievedCount }}</strong></span>
                    </div>
                </div>
            </section>

            {{-- 4. STATUS DISTRIBUTION BREAKDOWN --}}
            <section class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Distribusi Status Capaian Lahan</h2>
                        <p class="text-xs sm:text-sm text-slate-500">Ringkasan performa dekarbonisasi seluruh unit kebun pada tahun {{ $year }}.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 self-start sm:self-auto">
                        Total {{ $totalLands }} Unit Lahan
                    </span>
                </div>

                {{-- Visual Stacked Progress Bar --}}
                <div class="mt-6">
                    <div class="flex h-3 w-full overflow-hidden rounded-full bg-slate-100 p-0.5 gap-0.5 shadow-inner">
                        @if ($totalLands > 0)
                            <div class="h-full rounded-l-full bg-emerald-500 transition-all duration-500" style="width: {{ ($achievedCount / $totalLands) * 100 }}%" title="Tercapai: {{ $achievedCount }}"></div>
                            <div class="h-full bg-amber-400 transition-all duration-500" style="width: {{ ($inProgressCount / $totalLands) * 100 }}%" title="Dalam progres: {{ $inProgressCount }}"></div>
                            <div class="h-full bg-rose-400 transition-all duration-500" style="width: {{ ($notAchievedCount / $totalLands) * 100 }}%" title="Belum tercapai: {{ $notAchievedCount }}"></div>
                            <div class="h-full rounded-r-full bg-slate-300 transition-all duration-500" style="width: {{ ($incompleteCount / $totalLands) * 100 }}%" title="Data belum lengkap: {{ $incompleteCount }}"></div>
                        @else
                            <div class="h-full w-full rounded-full bg-slate-200"></div>
                        @endif
                    </div>
                </div>

                {{-- Status Pills --}}
                <div class="mt-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 transition hover:bg-emerald-50">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 text-xs font-bold text-emerald-800">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Target Tercapai
                            </span>
                            <span class="rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-extrabold text-emerald-800">{{ $achievedCount }}</span>
                        </div>
                        <p class="mt-2 text-xs text-emerald-700/80">Progres reduksi emisi ≥ 100%</p>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50/50 p-4 transition hover:bg-amber-50">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 text-xs font-bold text-amber-800">
                                <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                                Dalam Progres
                            </span>
                            <span class="rounded-md bg-amber-100 px-2 py-0.5 text-xs font-extrabold text-amber-800">{{ $inProgressCount }}</span>
                        </div>
                        <p class="mt-2 text-xs text-amber-700/80">Progres berjalan antara 1% – 99%</p>
                    </div>

                    <div class="rounded-xl border border-rose-100 bg-rose-50/50 p-4 transition hover:bg-rose-50">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 text-xs font-bold text-rose-800">
                                <span class="h-2 w-2 rounded-full bg-rose-400"></span>
                                Belum Tercapai
                            </span>
                            <span class="rounded-md bg-rose-100 px-2 py-0.5 text-xs font-extrabold text-rose-800">{{ $notAchievedCount }}</span>
                        </div>
                        <p class="mt-2 text-xs text-rose-700/80">Belum ada reduksi (emisi naik/tetap)</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 transition hover:bg-slate-100/60">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-2 text-xs font-bold text-slate-700">
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                Belum Lengkap
                            </span>
                            <span class="rounded-md bg-slate-200 px-2 py-0.5 text-xs font-extrabold text-slate-800">{{ $incompleteCount }}</span>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Target/emisi baseline belum tersedia</p>
                    </div>
                </div>
            </section>

            {{-- 5. SIMULATOR TARGET EMISI (INTERACTIVE DECISION TOOL) --}}
            <section class="overflow-hidden rounded-3xl border border-emerald-200/80 bg-white shadow-sm">
                <div class="border-b border-emerald-100 bg-gradient-to-r from-emerald-50 via-teal-50/50 to-white px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-600 text-white shadow-sm shadow-emerald-600/30">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">Kalkulator & Simulasi Target Emisi</h2>
                            <p class="text-xs sm:text-sm text-slate-500">Uji skenario persentase target untuk menghitung kuota batas emisi maksimal sebelum disimpan.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-8 p-6 lg:grid-cols-12 lg:items-start">
                    {{-- Form Controls (Col 5) --}}
                    <div class="space-y-5 lg:col-span-5">
                        {{-- Select Land --}}
                        <div>
                            <label for="simulation-land" class="mb-1.5 flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                                <span>Pilih Unit Lahan</span>
                                <span class="text-[11px] font-normal text-slate-400">Wajib</span>
                            </label>
                            <div class="relative">
                                <select id="simulation-land" class="w-full rounded-xl border border-slate-300 bg-slate-50/50 py-3 pl-4 pr-10 text-sm font-medium text-slate-900 shadow-sm transition hover:border-emerald-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                                    <option value="">-- Pilih lahan yang akan disimulasikan --</option>
                                    @foreach ($lands as $land)
                                        <option value="{{ $land->id }}"
                                                data-baseline="{{ $land->baseline_emission ?? '' }}"
                                                data-current="{{ $land->current_emission ?? '' }}"
                                                data-name="{{ $land->nama_lahan }}"
                                                data-target="{{ $land->target_pengurangan_emisi ?? '' }}">
                                            {{ $land->nama_lahan }} {{ $land->target_pengurangan_emisi ? '(Target saat ini: ' . number_format($land->target_pengurangan_emisi, 1) . '%)' : '(Belum ada target)' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Input Target Percentage --}}
                        <div>
                            <label for="simulation-target" class="mb-1.5 flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                                <span>Target Pengurangan Emisi (%)</span>
                                <span class="text-[11px] font-normal text-slate-400">Rentang 0.01% – 100%</span>
                            </label>
                            <div class="relative">
                                <input type="number"
                                       id="simulation-target"
                                       min="0.01"
                                       max="100"
                                       step="0.01"
                                       placeholder="Misal: 15.00"
                                       class="w-full rounded-xl border border-slate-300 bg-slate-50/50 py-3 pl-4 pr-12 text-sm font-semibold text-slate-900 shadow-sm transition hover:border-emerald-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">
                                    %
                                </div>
                            </div>

                            {{-- Preset target chips for convenience --}}
                            <div class="mt-2.5 flex items-center gap-1.5 flex-wrap">
                                <span class="text-[11px] text-slate-400 font-medium">Preset cepat:</span>
                                @foreach ([5, 10, 15, 20, 25] as $preset)
                                    <button type="button"
                                            onclick="document.getElementById('simulation-target').value = '{{ $preset }}'; document.getElementById('calculate-simulation').click();"
                                            class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-0.5 text-[11px] font-semibold text-slate-600 transition hover:bg-emerald-50 hover:border-emerald-300 hover:text-emerald-700">
                                        {{ $preset }}%
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Trigger Button --}}
                        <button type="button"
                                id="calculate-simulation"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-md shadow-emerald-600/20 transition-all hover:bg-emerald-700 hover:shadow-lg hover:shadow-emerald-600/30 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-emerald-500/20">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Hitung Skenario Simulasi</span>
                        </button>
                    </div>

                    {{-- Simulation Result Panel (Col 7) --}}
                    <div class="rounded-2xl border border-slate-200/80 bg-slate-50/70 p-5 lg:col-span-7">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                <h3 class="text-sm font-bold text-slate-800">Hasil Kalkulasi Skenario</h3>
                            </div>
                            <span class="text-xs text-slate-400">Tidak mempengaruhi database</span>
                        </div>

                        <p id="simulation-message" class="mt-3 text-xs sm:text-sm font-medium text-slate-600">
                            Pilih lahan dan masukkan persentase target untuk memprediksi batas emisi.
                        </p>

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {{-- Result 1: Maximum Allowed Emission --}}
                            <div class="rounded-xl border border-emerald-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-500">Batas Maksimal Emisi</span>
                                    <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800">Target Limit</span>
                                </div>
                                <div id="simulation-result" class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-emerald-700">
                                    - kg CO₂e
                                </div>
                                <p class="mt-1 text-[11px] text-slate-400">Emisi maksimal yang diizinkan sesuai target.</p>
                            </div>

                            {{-- Result 2: Current Actual Emission --}}
                            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-semibold text-slate-500">Emisi Aktual (Tahun {{ $year }})</span>
                                    <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[10px] font-bold text-slate-700">Realisasi</span>
                                </div>
                                <div id="simulation-current" class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-800">
                                    - kg CO₂e
                                </div>
                                <p class="mt-1 text-[11px] text-slate-400">Total emisi kotor aktual tahun berjalan.</p>
                            </div>
                        </div>

                        {{-- Comparison Delta Banner --}}
                        <div class="mt-4 rounded-xl border border-slate-200 bg-white p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Analisis Komparasi</p>
                            <p id="simulation-gap" class="mt-1 text-sm font-medium text-slate-600">
                                Hasil simulasi komparasi akan otomatis dianalisis setelah tombol hitung ditekan.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 6. LAND MONITORING & MANAGEMENT SECTION --}}
            <section class="rounded-3xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                {{-- Header Filter Bar --}}
                <div class="border-b border-slate-200/80 p-6 bg-slate-50/40">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">Kelola & Pantau Target Lahan</h2>
                            <p class="text-xs sm:text-sm text-slate-500">Tetapkan, perbarui, atau hapus target emisi untuk masing-masing unit perkebunan.</p>
                        </div>

                        {{-- Search & Status Filter Controls --}}
                        <div class="flex flex-col sm:flex-row gap-3">
                            {{-- Search Input --}}
                            <div class="relative min-w-[240px]">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="search"
                                       id="land-search"
                                       placeholder="Cari nama lahan..."
                                       class="w-full rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm font-medium text-slate-800 placeholder-slate-400 transition hover:border-emerald-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                            </div>

                            {{-- Filter Select --}}
                            <div class="relative min-w-[190px]">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                    </svg>
                                </div>
                                <select id="status-filter" class="w-full appearance-none rounded-xl border border-slate-300 bg-white py-2.5 pl-10 pr-9 text-sm font-medium text-slate-800 transition hover:border-emerald-400 focus:border-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-500/10">
                                    <option value="all">Semua Status</option>
                                    <option value="achieved">Tercapai (≥100%)</option>
                                    <option value="progress">Dalam Progres (1-99%)</option>
                                    <option value="not-achieved">Belum Tercapai (≤0%)</option>
                                    <option value="incomplete">Belum Lengkap</option>
                                </select>
                                <svg class="pointer-events-none absolute right-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cards Grid Container --}}
                <div class="grid grid-cols-1 gap-6 p-6 lg:grid-cols-2" id="land-list">
                    @forelse ($lands as $land)
                        @php
                            $progress = $land->progress_percentage;
                            $reduction = $land->reduction_percentage;
                            $target = $land->target_pengurangan_emisi;
                            $baseline = $land->baseline_emission;

                            if ($target === null) {
                                $status = 'incomplete';
                                $statusLabel = 'Target Belum Ditetapkan';
                                $statusBadgeClass = 'border-slate-200 bg-slate-100 text-slate-700';
                                $barClass = 'bg-slate-300';
                                $cardBorderHover = 'hover:border-slate-300';
                            } elseif ($progress === null) {
                                $status = 'incomplete';
                                $statusLabel = 'Data Emisi Belum Lengkap';
                                $statusBadgeClass = 'border-slate-200 bg-slate-100 text-slate-700';
                                $barClass = 'bg-slate-300';
                                $cardBorderHover = 'hover:border-slate-300';
                            } elseif ($progress >= 100) {
                                $status = 'achieved';
                                $statusLabel = 'Target Tercapai';
                                $statusBadgeClass = 'border-emerald-200 bg-emerald-50 text-emerald-800';
                                $barClass = 'bg-emerald-500';
                                $cardBorderHover = 'hover:border-emerald-300';
                            } elseif ($progress > 0) {
                                $status = 'progress';
                                $statusLabel = 'Dalam Progres';
                                $statusBadgeClass = 'border-amber-200 bg-amber-50 text-amber-800';
                                $barClass = 'bg-amber-400';
                                $cardBorderHover = 'hover:border-amber-300';
                            } else {
                                $status = 'not-achieved';
                                $statusLabel = 'Belum Tercapai';
                                $statusBadgeClass = 'border-rose-200 bg-rose-50 text-rose-800';
                                $barClass = 'bg-rose-400';
                                $cardBorderHover = 'hover:border-rose-300';
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
                            $hasError = $errorBag->has('target_pengurangan_emisi');
                        @endphp

                        <article class="land-card group relative flex flex-col justify-between rounded-2xl border {{ $hasError ? 'border-rose-300 ring-2 ring-rose-200' : 'border-slate-200/90' }} bg-white p-5 sm:p-6 shadow-sm transition-all duration-200 hover:shadow-md {{ $cardBorderHover }}"
                                 data-name="{{ strtolower($land->nama_lahan) }}"
                                 data-status="{{ $status }}">

                            <div>
                                {{-- Card Header: Title, Location, and Status Badge --}}
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3.5">
                                        {{-- Initial Icon --}}
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-100 text-lg font-extrabold text-emerald-800 ring-1 ring-emerald-200/60 shadow-sm">
                                            {{ strtoupper(substr($land->nama_lahan, 0, 1)) }}
                                        </div>

                                        <div>
                                            <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition">
                                                {{ $land->nama_lahan }}
                                            </h3>
                                            <div class="mt-0.5 flex flex-wrap items-center gap-2 text-xs text-slate-500 font-medium">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    {{ $land->lokasi ?? 'Lokasi tidak tersedia' }}
                                                </span>
                                                <span>•</span>
                                                <span>Luas: <strong class="text-slate-700">{{ number_format((float) $land->luas_area, 1) }} Ha</strong></span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Status Pill --}}
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-bold {{ $statusBadgeClass }} shadow-xs">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $status === 'achieved' ? 'bg-emerald-500' : ($status === 'progress' ? 'bg-amber-400' : ($status === 'not-achieved' ? 'bg-rose-500' : 'bg-slate-400')) }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                {{-- Emission Metrics 2x2 Grid --}}
                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    {{-- Baseline --}}
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Baseline ({{ $previousYear }})</p>
                                        <p class="mt-1 text-base sm:text-lg font-bold text-slate-800">
                                            {{ $baseline !== null ? number_format((float) $baseline, 2) : '-' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium">kg CO₂e</p>
                                    </div>

                                    {{-- Current Emission --}}
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Emisi Aktual ({{ $year }})</p>
                                        <p class="mt-1 text-base sm:text-lg font-bold text-slate-800">
                                            {{ $land->current_emission !== null ? number_format((float) $land->current_emission, 2) : '-' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium">kg CO₂e</p>
                                    </div>

                                    {{-- Actual Reduction % --}}
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Reduksi Aktual</p>
                                        <p class="mt-1 text-base sm:text-lg font-bold {{ $reduction !== null && $reduction >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                            @if ($reduction !== null)
                                                {{ $reduction >= 0 ? '↓' : '↑' }} {{ number_format(abs($reduction), 2) }}%
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium">vs baseline</p>
                                    </div>

                                    {{-- Target Emission Cap --}}
                                    <div class="rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Batas Emisi Target</p>
                                        <p class="mt-1 text-base sm:text-lg font-bold text-slate-800">
                                            {{ $limit !== null ? number_format($limit, 2) : '-' }}
                                        </p>
                                        <p class="text-[10px] text-slate-400 font-medium">kg CO₂e max</p>
                                    </div>
                                </div>

                                {{-- Target Progress Bar --}}
                                <div class="mt-5 space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-semibold text-slate-600">Progres Capaian Target</span>
                                        <span class="font-extrabold text-slate-900">
                                            {{ $progress !== null ? number_format($progress, 1) . '%' : '0%' }}
                                        </span>
                                    </div>
                                    <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                        <div class="h-full rounded-full {{ $barClass }} transition-all duration-500"
                                             style="width: {{ $progress !== null ? max(0, min(100, $progress)) : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                {{-- Evaluation & Insight Box --}}
                                <div class="mt-4 rounded-xl p-3.5 text-xs font-medium leading-relaxed
                                    {{ $status === 'achieved'
                                        ? 'border border-emerald-200 bg-emerald-50/80 text-emerald-800'
                                        : ($status === 'not-achieved'
                                            ? 'border border-rose-200 bg-rose-50/80 text-rose-800'
                                            : ($status === 'progress'
                                                ? 'border border-amber-200 bg-amber-50/80 text-amber-800'
                                                : 'border border-slate-200 bg-slate-50 text-slate-600')) }}">
                                    <div class="flex items-start gap-2">
                                        <div class="mt-0.5 shrink-0">
                                            @if ($status === 'achieved')
                                                <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif ($status === 'progress')
                                                <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif ($status === 'not-achieved')
                                                <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            @if ($target === null)
                                                Target belum ditetapkan. Masukkan target persentase di bawah untuk mengaktifkan batas emisi.
                                            @elseif ($progress === null)
                                                Data emisi belum lengkap. Sistem memerlukan baseline emisi {{ $previousYear }} dan aktual {{ $year }} untuk mengukur progres.
                                            @elseif ($status === 'achieved')
                                                <strong>Target tercapai!</strong> Penurunan emisi berjalan efektif. Pertahankan tata kelola lahan agar hasil tetap konsisten.
                                            @elseif ($status === 'progress')
                                                Reduksi aktual masih selisih <strong>{{ number_format(max(0, $gap), 2) }}%</strong> dari target. Evaluasi efisiensi pupuk dan bahan bakar.
                                            @else
                                                Pengurangan emisi belum mencapai target. Emisi tahun ini melampaui batas yang direncanakan.
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Footer: Target Configuration Actions --}}
                            <div class="mt-6 border-t border-slate-100 pt-5">
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                                        {{ $target === null ? 'Tetapkan Target Emisi' : 'Konfigurasi Target' }}
                                    </h4>

                                    @if ($target !== null)
                                        <span class="inline-flex items-center gap-1 rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-extrabold text-emerald-700 ring-1 ring-emerald-200">
                                            Aktif: {{ number_format((float) $target, 2) }}%
                                        </span>
                                    @endif
                                </div>

                                {{-- Target Update/Create Form --}}
                                <form method="POST"
                                      action="{{ route('esg.targets.update', $land->id) }}"
                                      class="space-y-3">
                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden" name="year" value="{{ $year }}">

                                    <div class="flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center">
                                        <div class="relative flex-1">
                                            <input type="number"
                                                   id="target-{{ $land->id }}"
                                                   name="target_pengurangan_emisi"
                                                   min="0.01"
                                                   max="100"
                                                   step="0.01"
                                                   required
                                                   value="{{ old('target_pengurangan_emisi', $target) }}"
                                                   placeholder="Contoh: 15.00"
                                                   class="w-full rounded-xl border {{ $hasError ? 'border-rose-400 bg-rose-50/50' : 'border-slate-300 bg-slate-50/40' }} py-2.5 pl-3.5 pr-8 text-sm font-semibold text-slate-900 shadow-xs transition hover:border-emerald-400 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-3 focus:ring-emerald-500/15">
                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400">
                                                %
                                            </span>
                                        </div>

                                        <button type="submit"
                                                class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 active:scale-[0.98] focus:outline-none focus:ring-3 focus:ring-emerald-500/20">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>{{ $target === null ? 'Tetapkan Target' : 'Simpan Perubahan' }}</span>
                                        </button>
                                    </div>

                                    @if ($hasError)
                                        <p class="text-xs font-semibold text-rose-600 flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ $errorBag->first('target_pengurangan_emisi') }}</span>
                                        </p>
                                    @endif
                                </form>

                                {{-- Delete Target Button (Only if target exists) --}}
                                @if ($target !== null)
                                    <div class="mt-2.5 flex justify-end">
                                        <form method="POST"
                                              action="{{ route('esg.targets.destroy', $land->id) }}"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus target pengurangan emisi lahan {{ $land->nama_lahan }}? Data lahan dan riwayat emisi tetap aman.')">
                                            @csrf
                                            @method('DELETE')

                                            <input type="hidden" name="year" value="{{ $year }}">

                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline transition">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus Target Lahan</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>

                    @empty
                        <div class="rounded-2xl border-2 border-dashed border-slate-200 bg-white p-12 text-center lg:col-span-2">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-base font-bold text-slate-800">Belum Ada Data Lahan</h3>
                            <p class="mt-1 text-xs sm:text-sm text-slate-500">Data lahan belum tersedia di sistem. Hubungi administrator atau kelola modul lahan.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Empty Search/Filter Message --}}
                <div id="empty-filter" class="hidden py-14 px-6 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h4 class="mt-3 text-sm font-bold text-slate-800">Tidak ada lahan ditemukan</h4>
                    <p class="mt-1 text-xs text-slate-500">Coba ubah kata kunci pencarian atau ganti filter status di atas.</p>
                </div>
            </section>

            {{-- 7. FOOTER INFORMATION BANNER --}}
            <aside class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-xs">
                <div class="flex items-start gap-3.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-xs text-slate-600 leading-relaxed space-y-1">
                        <p class="font-bold text-slate-800">Pedoman Perhitungan & Tata Kelola Target Emisi</p>
                        <p>Target pengurangan dihitung berbasis perbandingan emisi total kotor tahun berjalan terhadap baseline tahun sebelumnya. Seluruh nilai target dapat disesuaikan oleh <strong>ESG Officer</strong>. Simulasi target bersifat kalkulatif di sisi klien dan tidak mengubah konfigurasi aktif sampai tombol simpan ditekan.</p>
                    </div>
                </div>
            </aside>

        </div>
    </div>

    {{-- 8. CLIENT JAVASCRIPT: SIMULATOR & REAL-TIME FILTERING --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // === A. SIMULATOR LOGIC ===
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
                gapText.textContent = 'Hasil simulasi komparasi akan otomatis dianalisis setelah tombol hitung ditekan.';
                gapText.className = 'mt-1 text-sm font-medium text-slate-600';
            }

            // Sync simulation target default when a land is selected
            landSelect.addEventListener('change', function () {
                const option = landSelect.options[landSelect.selectedIndex];

                if (!landSelect.value) {
                    targetInput.value = '';
                    message.textContent = 'Pilih lahan untuk memulai simulasi.';
                    resetSimulation();
                    return;
                }

                targetInput.value = option.dataset.target || '';
                message.textContent = 'Lahan terpilih: ' + option.dataset.name + '. Tentukan persentase target untuk simulasi.';
                resetSimulation();
            });

            // Calculate simulation result
            calculateButton.addEventListener('click', function () {
                const option = landSelect.options[landSelect.selectedIndex];
                const target = Number(targetInput.value);

                if (!landSelect.value) {
                    message.textContent = 'Silakan pilih lahan terlebih dahulu.';
                    message.className = 'mt-3 text-xs sm:text-sm font-bold text-rose-600';
                    return;
                }

                if (
                    targetInput.value === '' ||
                    !Number.isFinite(target) ||
                    target <= 0 ||
                    target > 100
                ) {
                    message.textContent = 'Target harus berupa angka lebih dari 0% dan maksimal 100%.';
                    message.className = 'mt-3 text-xs sm:text-sm font-bold text-rose-600';
                    resetSimulation();
                    return;
                }

                message.className = 'mt-3 text-xs sm:text-sm font-medium text-slate-700';

                const baseline = Number(option.dataset.baseline);
                const currentRaw = option.dataset.current;

                if (!option.dataset.baseline || baseline <= 0) {
                    message.textContent = 'Data baseline emisi tahun ' + {{ $previousYear }} + ' belum tersedia untuk ' + option.dataset.name + '. Simulasi tidak dapat dihitung.';
                    message.className = 'mt-3 text-xs sm:text-sm font-bold text-amber-600';
                    resetSimulation();
                    return;
                }

                const limit = baseline * (1 - target / 100);

                result.textContent = limit.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg CO₂e';
                message.textContent = 'Skenario ' + option.dataset.name + ' dengan target reduksi ' + target + '% berhasil dihitung.';

                if (currentRaw === '' || currentRaw === null) {
                    currentText.textContent = 'Belum tersedia';
                    gapText.textContent = 'Batas kuota emisi maksimal telah dihitung. Data emisi aktual tahun ' + {{ $year }} + ' belum tercatat untuk dibandingkan.';
                    gapText.className = 'mt-1 text-sm font-medium text-slate-500';
                    return;
                }

                const current = Number(currentRaw);
                const difference = current - limit;

                currentText.textContent = current.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg CO₂e';

                if (difference <= 0) {
                    gapText.innerHTML = '<span class="inline-flex items-center gap-1 text-emerald-700 font-bold">✓ Target Terpenuhi:</span> Emisi aktual berada ' + Math.abs(difference).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg CO₂e di bawah batas ambang target simulasi.';
                    gapText.className = 'mt-1 text-sm font-medium text-emerald-800';
                } else {
                    gapText.innerHTML = '<span class="inline-flex items-center gap-1 text-rose-700 font-bold">✕ Melebihi Batas:</span> Emisi aktual melampaui batas ambang target simulasi sebesar ' + difference.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' kg CO₂e.';
                    gapText.className = 'mt-1 text-sm font-medium text-rose-700';
                }
            });

            // === B. SEARCH & STATUS FILTERING LOGIC ===
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
                    const matchesStatus = (selectedStatus === 'all') || (card.dataset.status === selectedStatus);

                    const show = matchesName && matchesStatus;
                    card.classList.toggle('hidden', !show);

                    if (show) visible++;
                });

                if (empty) {
                    empty.classList.toggle('hidden', visible > 0);
                }
            }

            search.addEventListener('input', filterLands);
            filter.addEventListener('change', filterLands);
        });
    </script>
</x-app-layout>
