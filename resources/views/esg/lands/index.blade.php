
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-emerald-600">
                    ESG MANAGEMENT SYSTEM
                </p>
                <h2 class="mt-1 text-2xl font-bold tracking-tight text-gray-900">
                    Manajemen Lahan
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Kelola dan pantau seluruh data lahan operasional.
                </p>
            </div>

            <a href="{{ route('esg.lands.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:-translate-y-0.5 hover:bg-emerald-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Lahan
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-slate-50 py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
                    <svg class="h-5 w-5 shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->has('hapus'))
                <div class="flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
                    <svg class="h-5 w-5 shrink-0" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.24 3.86a2 2 0 00-2.94 0z"/>
                    </svg>
                    <p class="text-sm font-medium">{{ $errors->first('hapus') }}</p>
                </div>
            @endif

            {{-- Hero Section --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-700 p-7 shadow-xl sm:p-9">
                <div class="relative z-10 max-w-2xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-medium text-emerald-50">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        Land Management
                    </div>

                    <h3 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Kelola Lahan Lebih Mudah
                    </h3>

                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-emerald-100 sm:text-base">
                        Pantau informasi lahan, luas area, target pengurangan
                        emisi, dan status operasional dalam satu dashboard.
                    </p>

                    <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-emerald-100">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Data lahan terorganisir
                        </span>
                        <span class="hidden sm:inline">•</span>
                        <span>Monitoring data emisi</span>
                    </div>
                </div>

                <div class="absolute -right-12 -top-20 h-72 w-72 rounded-full border-[35px] border-white/5"></div>
                <div class="absolute -bottom-28 right-28 h-64 w-64 rounded-full bg-emerald-400/10 blur-2xl"></div>

                <svg class="absolute bottom-5 right-8 hidden h-36 w-36 text-white/10 sm:block"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.1" d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                    <path stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.1" d="M12 18s-5-2.5-5-7V7l5-2 5 2v4c0 4.5-5 7-5 7z"/>
                </svg>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Total Data Lahan
                            </p>
                            <h3 class="mt-2 text-3xl font-bold text-gray-900">
                                {{ $lands->total() }}
                            </h3>
                            <p class="mt-1 text-xs text-gray-400">
                                Seluruh lahan terdaftar
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                            <svg class="h-6 w-6" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8" d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9v.01M9 12v.01M9 15v.01M9 18v.01"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">
                                Entri Emisi
                            </p>
                            <h3 class="mt-2 text-3xl font-bold text-gray-900">
                                {{ $lands->sum('emissions_count') }}
                            </h3>
                            <p class="mt-1 text-xs text-gray-400">
                                Pada halaman ini
                            </p>
                        </div>
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                            <svg class="h-6 w-6" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8" d="M7 3v4m10-4v4M4 9h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.8" d="M8 13h3m-3 4h3m3-4h2"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Data --}}
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Daftar Data Lahan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Informasi seluruh lahan yang terdaftar dalam sistem.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                        {{ $lands->total() }} Lahan Terdaftar
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-slate-50">
                            <tr>
                                @foreach (['No', 'Nama Lahan', 'Lokasi', 'Luas Area', 'Target Pengurangan', 'Entri Emisi', 'Status', 'Aksi'] as $heading)
                                    <th class="whitespace-nowrap px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                                        {{ $heading }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($lands as $index => $land)
                                <tr class="transition hover:bg-emerald-50/40">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">
                                        {{ $lands->firstItem() + $index }}
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                                                <svg class="h-5 w-5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.8" d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                                                </svg>
                                            </div>
                                            <span class="font-semibold text-gray-900">
                                                {{ $land->nama_lahan }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8" d="M12 21s8-7.5 8-13a8 8 0 10-16 0c0 5.5 8 13 8 13z"/>
                                                <circle cx="12" cy="8" r="2.5"
                                                    stroke-width="1.8"/>
                                            </svg>
                                            {{ $land->lokasi }}
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-700">
                                        {{ number_format($land->luas_area, 2, ',', '.') }}
                                        <span class="text-xs text-gray-400">ha</span>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700">
                                            {{ number_format($land->target_pengurangan_emisi ?? 0, 2, ',', '.') }}%
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-slate-100 px-2 text-sm font-bold text-gray-700">
                                            {{ $land->emissions_count }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        @if ($land->status_operasional === 'aktif')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('esg.lands.edit', $land) }}"
                                                title="Edit Lahan"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100">
                                                <svg class="h-4 w-4" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7 1l9-9a2.1 2.1 0 00-3-3l-9 9-1 4 4-1z"/>
                                                </svg>
                                            </a>

                                            <form action="{{ route('esg.lands.destroy', $land) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data lahan ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    title="Hapus Lahan"
                                                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100">
                                                    <svg class="h-4 w-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M4 7h16M10 11v6m4-6v6M5 7l1 14h12l1-14M9 7V4h6v3"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                            <svg class="h-8 w-8" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.8" d="M3 21h18M5 21V7l8-4v18"/>
                                            </svg>
                                        </div>
                                        <h4 class="mt-4 font-semibold text-gray-900">
                                            Belum Ada Data Lahan
                                        </h4>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Mulai dengan menambahkan data lahan pertama Anda.
                                        </p>
                                        <a href="{{ route('esg.lands.create') }}"
                                            class="mt-5 inline-flex items-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                            Tambah Lahan
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-6 py-4">
                    {{ $lands->links() }}
                </div>
            </div>

            <p class="pb-3 text-center text-xs text-gray-400">
                ESG Management System · Land Management
            </p>
        </div>
    </div>
</x-app-layout>
