
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">
                    ESG MANAGEMENT SYSTEM
                </p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                    Edit Data Lahan
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Perbarui informasi dan status operasional lahan.
                </p>
            </div>

            <a href="{{ route('esg.lands.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-600 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-50 hover:text-emerald-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Hero Section --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-700 p-7 shadow-lg sm:p-8">
                <div class="relative z-10 max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-emerald-50">
                        <svg class="h-4 w-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/>
                        </svg>
                        Land Update
                    </div>

                    <h3 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Perbarui Informasi Lahan
                    </h3>

                    <p class="mt-3 text-sm leading-relaxed text-emerald-100 sm:text-base">
                        Lakukan perubahan informasi lahan agar data pengelolaan
                        dan pemantauan ESG tetap akurat.
                    </p>
                </div>

                <div class="absolute -right-10 -top-20 h-64 w-64 rounded-full border-[30px] border-white/5"></div>
                <div class="absolute -bottom-24 right-20 h-52 w-52 rounded-full bg-emerald-400/10 blur-2xl"></div>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                            fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.24 3.86a2 2 0 00-2.94 0z"/>
                        </svg>

                        <div>
                            <h3 class="font-semibold text-red-800">
                                Terdapat kesalahan pengisian data
                            </h3>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Informasi Lahan --}}
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm">
                        <svg class="h-6 w-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="1.8" d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">
                            Lahan yang sedang diedit
                        </p>
                        <h3 class="mt-1 text-lg font-bold text-gray-900">
                            {{ $land->nama_lahan }}
                        </h3>
                    </div>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <svg class="h-6 w-6" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8" d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/>
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900">
                                Perbarui Informasi Lahan
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Pastikan informasi yang diperbarui sudah benar.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('esg.lands.update', $land) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('esg.lands._form')
                </form>
            </div>

            <p class="pb-3 text-center text-xs text-gray-400">
                ESG Management System · Land Management
            </p>
        </div>
    </div>
</x-app-layout>
