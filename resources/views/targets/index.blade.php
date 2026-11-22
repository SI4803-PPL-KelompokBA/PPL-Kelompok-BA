<x-app-layout>

    @php

        // Ringkasan dihitung dari data yang sudah dikirim controller.

        $withProgress   = $lands->filter(fn ($l) => $l->progress_percentage !== null);

        $achievedCount  = $withProgress->filter(fn ($l) => $l->progress_percentage >= 100)->count();

        $reductions     = $lands->pluck('reduction_percentage')->filter(fn ($v) => $v !== null);

        $avgReduction   = $reductions->isNotEmpty() ? $reductions->avg() : null;

        $totalCurrent   = $lands->sum('current_emission');

        $incompleteCount = $lands->count() - $withProgress->count();

    @endphp



    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-8">

        <div class="mx-auto max-w-7xl space-y-6">



            {{-- HEADER + FILTER TAHUN --}}

            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

                <div>

                    <nav class="flex items-center gap-1.5 text-sm text-slate-400" aria-label="Breadcrumb">

                        <span>ESG</span>

                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>

                        </svg>

                        <span class="font-medium text-slate-600">Target emisi</span>

                    </nav>



                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">

                        Target pengurangan emisi

                    </h1>

                    <p class="mt-1.5 max-w-xl text-sm leading-relaxed text-slate-500">

                        Tetapkan target per lahan dan pantau seberapa jauh emisi {{ $year }}

                        turun dibanding {{ $previousYear }}.

                    </p>

                </div>



                <form method="GET" action="{{ route('esg.targets.index') }}"

                      class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 shadow-sm">

                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"

                              d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>

                    </svg>



                    <div class="leading-tight">

                        <label for="year" class="block text-xs font-medium text-slate-500">Tahun pemantauan</label>

                        <select name="year" id="year"

                                onchange="this.form.submit()"

                                class="-ml-1 mt-0.5 border-0 bg-transparent py-0 pl-1 pr-8 text-base font-semibold text-slate-900 focus:ring-0">

                            @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)

                                <option value="{{ $y }}" {{ (int) $year === $y ? 'selected' : '' }}>

                                    {{ $y }}

                                </option>

                            @endfor

                        </select>

                    </div>



                    <noscript>

                        <button type="submit"

                                class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white">

                            Tampilkan

                        </button>

                    </noscript>

                </form>

            </div>



            <x-flash />



            {{-- RINGKASAN --}}

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">



                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <p class="text-sm text-slate-500">Lahan dikelola</p>

                    <p class="mt-2 text-3xl font-bold tabular-nums text-slate-900">{{ $lands->count() }}</p>

                    <p class="mt-1 text-xs text-slate-400">

                        @if ($incompleteCount > 0)

                            {{ $incompleteCount }} lahan belum punya baseline atau target

                        @else

                            Semua lahan sudah punya baseline dan target

                        @endif

                    </p>

                </div>



                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <p class="text-sm text-slate-500">Total net emisi {{ $year }}</p>

                    <p class="mt-2 flex items-baseline gap-1.5">

                        <span class="text-3xl font-bold tabular-nums text-slate-900">

                            {{ number_format((float) $totalCurrent, 2) }}

                        </span>

                        <span class="text-sm text-slate-400">kg CO₂e</span>

                    </p>

                    <p class="mt-1 text-xs text-slate-400">Seluruh lahan, setelah dikurangi penyerapan</p>

                </div>



                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <p class="text-sm text-slate-500">Rata-rata reduksi</p>

                    @if ($avgReduction !== null)

                        <p class="mt-2 text-3xl font-bold tabular-nums {{ $avgReduction >= 0 ? 'text-green-700' : 'text-red-600' }}">

                            {{ number_format($avgReduction, 2) }}%

                        </p>

                        <p class="mt-1 text-xs text-slate-400">

                            {{ $avgReduction >= 0 ? 'Turun' : 'Naik' }} dibanding {{ $previousYear }}

                        </p>

                    @else

                        <p class="mt-2 text-lg font-semibold text-slate-400">Belum tersedia</p>

                        <p class="mt-1 text-xs text-slate-400">Belum ada lahan dengan baseline</p>

                    @endif

                </div>



                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <p class="text-sm text-slate-500">Target tercapai</p>

                    <p class="mt-2 flex items-baseline gap-1.5">

                        <span class="text-3xl font-bold tabular-nums text-slate-900">{{ $achievedCount }}</span>

                        <span class="text-sm text-slate-400">dari {{ $withProgress->count() }} lahan</span>

                    </p>

                    <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100">

                        <div class="h-full rounded-full bg-green-500"

                             style="width: {{ $withProgress->count() > 0 ? round($achievedCount / $withProgress->count() * 100) : 0 }}%"></div>

                    </div>

                </div>



            </div>



            {{-- TABEL TARGET --}}

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">



                <div class="flex flex-col gap-1 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <div>

                        <h2 class="text-lg font-bold text-slate-900">Pemantauan per lahan</h2>

                        <p class="mt-0.5 text-sm text-slate-500">

                            Ubah target di kolom target, lalu klik Simpan pada baris yang sama.

                        </p>

                    </div>

                </div>



                <div class="overflow-x-auto">

                    <table class="min-w-full text-sm">



                        <thead class="border-b border-slate-200 bg-slate-50 text-left text-xs font-semibold text-slate-500">

                            <tr>

                                <th scope="col" class="px-5 py-3.5 sm:px-6">Lahan</th>

                                <th scope="col" class="px-5 py-3.5 text-right">Baseline {{ $previousYear }}</th>

                                <th scope="col" class="px-5 py-3.5 text-right">Net emisi {{ $year }}</th>

                                <th scope="col" class="px-5 py-3.5">Reduksi</th>

                                <th scope="col" class="px-5 py-3.5">Target</th>

                                <th scope="col" class="px-5 py-3.5 sm:pr-6">Progres</th>

                            </tr>

                        </thead>



                        <tbody class="divide-y divide-slate-100">



                            @forelse ($lands as $land)

                                @php

                                    $bag      = 'target_' . $land->id;

                                    $hasError = $errors->hasBag($bag) && $errors->getBag($bag)->has('target_pengurangan_emisi');

                                    $progress = $land->progress_percentage;

                                    $reduction = $land->reduction_percentage;



                                    if ($progress === null) {

                                        $barColor = 'bg-slate-300';

                                        $statusText = null;

                                    } elseif ($progress >= 100) {

                                        $barColor = 'bg-green-500';

                                        $statusText = ['Tercapai', 'text-green-700'];

                                    } elseif ($progress > 0) {

                                        $barColor = 'bg-amber-400';

                                        $statusText = ['Berjalan', 'text-amber-700'];

                                    } else {

                                        $barColor = 'bg-red-400';

                                        $statusText = ['Belum tercapai', 'text-red-600'];

                                    }

                                @endphp



                                <tr class="align-top transition-colors hover:bg-slate-50/70">



                                    {{-- LAHAN --}}

                                    <td class="whitespace-nowrap px-5 py-4 sm:px-6">

                                        <div class="flex items-center gap-3">

                                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-50 text-sm font-bold text-green-700">

                                                {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($land->nama_lahan, 0, 1)) }}

                                            </span>

                                            <div>

                                                <p class="font-semibold text-slate-900">{{ $land->nama_lahan }}</p>

                                                <p class="mt-0.5 text-xs text-slate-500">{{ $land->lokasi }}</p>

                                            </div>

                                        </div>

                                    </td>



                                    {{-- BASELINE --}}

                                    <td class="whitespace-nowrap px-5 py-4 text-right tabular-nums">

                                        @if ($land->baseline_emission !== null)

                                            <span class="font-medium text-slate-700">

                                                {{ number_format((float) $land->baseline_emission, 2) }}

                                            </span>

                                            <span class="ml-0.5 text-xs text-slate-400">kg CO₂e</span>

                                        @else

                                            <span class="text-xs text-slate-400">Belum tersedia</span>

                                        @endif

                                    </td>



                                    {{-- EMISI SAAT INI --}}

                                    <td class="whitespace-nowrap px-5 py-4 text-right tabular-nums">

                                        <span class="font-medium text-slate-700">

                                            {{ number_format((float) $land->current_emission, 2) }}

                                        </span>

                                        <span class="ml-0.5 text-xs text-slate-400">kg CO₂e</span>

                                    </td>



                                    {{-- REDUKSI --}}

                                    <td class="whitespace-nowrap px-5 py-4">

                                        @if ($reduction !== null)

                                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold tabular-nums

                                                {{ $reduction >= 0 ? 'bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20' : 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20' }}">

                                                <svg class="h-3 w-3 {{ $reduction >= 0 ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 5v14m0 0l-6-6m6 6l6-6"/>

                                                </svg>

                                                {{ number_format(abs($reduction), 2) }}%

                                            </span>

                                            <span class="sr-only">{{ $reduction >= 0 ? 'turun' : 'naik' }}</span>

                                        @else

                                            <span class="text-xs text-slate-400">Belum tersedia</span>

                                        @endif

                                    </td>



                                    {{-- INPUT TARGET --}}

                                    <td class="px-5 py-4">

                                        <form method="POST"

                                              action="{{ route('esg.targets.update', $land->id) }}"

                                              class="flex items-center gap-2">

                                            @csrf

                                            @method('PATCH')



                                            <input type="hidden" name="year" value="{{ $year }}">



                                            <div class="relative">

                                                <input type="number"

                                                       name="target_pengurangan_emisi"

                                                       value="{{ $hasError ? old('target_pengurangan_emisi') : $land->target_pengurangan_emisi }}"

                                                       min="0" max="100" step="0.01" required

                                                       class="w-28 rounded-lg py-2 pl-3 pr-8 text-sm tabular-nums shadow-sm

                                                              {{ $hasError

                                                                  ? 'border-red-400 focus:border-red-500 focus:ring-red-500'

                                                                  : 'border-slate-300 focus:border-green-500 focus:ring-green-500' }}"

                                                       aria-label="Target pengurangan emisi untuk {{ $land->nama_lahan }}"

                                                       @if ($hasError) aria-invalid="true" @endif>

                                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-slate-400">%</span>

                                            </div>



                                            <button type="submit"

                                                    class="rounded-lg bg-green-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">

                                                Simpan

                                            </button>

                                        </form>



                                        @if ($hasError)

                                            <p class="mt-1.5 text-xs text-red-600" role="alert">

                                                {{ $errors->getBag($bag)->first('target_pengurangan_emisi') }}

                                            </p>

                                        @endif

                                    </td>



                                    {{-- PROGRES --}}

                                    <td class="min-w-52 px-5 py-4 sm:pr-6">

                                        @if ($land->baseline_emission === null || $progress === null)

                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs text-slate-500">

                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">

                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"

                                                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                                                </svg>

                                                Belum ada baseline / target

                                            </span>

                                        @else

                                            <div class="flex items-center justify-between text-xs">

                                                <span class="font-semibold {{ $statusText[1] }}">{{ $statusText[0] }}</span>

                                                <span class="font-semibold tabular-nums text-slate-700">

                                                    {{ number_format($progress, 2) }}%

                                                </span>

                                            </div>

                                            <div class="mt-1.5 h-2.5 overflow-hidden rounded-full bg-slate-100"

                                                 role="progressbar"

                                                 aria-valuenow="{{ round($progress) }}"

                                                 aria-valuemin="0" aria-valuemax="100"

                                                 aria-label="Progres target {{ $land->nama_lahan }}">

                                                <div class="h-full rounded-full {{ $barColor }}"

                                                     style="width: {{ max(0, min(100, $progress)) }}%"></div>

                                            </div>

                                        @endif

                                    </td>



                                </tr>



                            @empty

                                <tr>

                                    <td colspan="6" class="px-5 py-16 text-center">

                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">

                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"

                                                      d="M3 7l9-4 9 4-9 4-9-4zm0 5l9 4 9-4M3 17l9 4 9-4"/>

                                            </svg>

                                        </div>

                                        <p class="mt-3 font-semibold text-slate-700">Belum ada data lahan</p>

                                        <p class="mt-1 text-sm text-slate-400">

                                            Lahan akan muncul di sini setelah ditambahkan ke sistem.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse



                        </tbody>

                    </table>

                </div>



                <div class="flex flex-col gap-2 border-t border-slate-100 bg-slate-50 px-5 py-3.5 sm:flex-row sm:items-center sm:justify-between sm:px-6">

                    <p class="text-xs text-slate-500">

                        Progres = reduksi emisi dibanding {{ $previousYear }} ÷ target. Ini perbandingan tahun ke tahun yang disederhanakan.

                    </p>



                    <div class="flex items-center gap-4 text-xs text-slate-500">

                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-green-500"></span>Tercapai</span>

                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-amber-400"></span>Berjalan</span>

                        <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-400"></span>Belum tercapai</span>

                    </div>

                </div>



            </div>



        </div>

    </div>

</x-app-layout>