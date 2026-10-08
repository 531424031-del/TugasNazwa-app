@extends('layouts.app')

@section('title', 'Tabungan')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Tabungan</h1>
            <p class="mt-1 text-sm text-slate-500">Riwayat setoran dan progress menuju targetmu.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('savings.settings') }}" class="btn-outline">@icon('settings', 15) Ubah target</a>
            <a href="{{ route('savings.create') }}" class="btn-primary">@icon('plus', 15) Catat Setoran</a>
        </div>
    </div>

    {{-- Progress --}}
    <div class="card mb-6 p-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <p class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
                    @icon('target', 16) Total Tabungan Terkumpul
                </p>
                <div class="mt-2 flex items-end gap-2">
                    <p class="text-3xl font-extrabold tracking-tight text-slate-900">{{ format_rupiah($total) }}</p>
                    <p class="pb-1 text-sm font-semibold text-slate-400">dari {{ format_rupiah($target) }}</p>
                </div>
            </div>
            <span class="badge bg-indigo-50 text-indigo-700">{{ number_format($progress, 0) }}% tercapai</span>
        </div>

        <div class="mt-4 progress-track">
            <div class="progress-bar bg-gradient-to-r from-indigo-500 to-violet-500"
                 style="width: {{ number_format($progress, 1) }}%"></div>
        </div>

        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs font-medium">
            <span class="text-slate-400">Target harian {{ format_rupiah($setting->daily_amount) }}</span>
            <span class="{{ $hasSavedToday ? 'text-emerald-600' : 'text-amber-600' }} font-bold">
                {{ $hasSavedToday ? 'Sudah menabung hari ini' : 'Belum menabung hari ini' }}
            </span>
        </div>

        @unless ($hasSavedToday)
            <div class="mt-4 flex flex-col gap-3 rounded-xl border border-indigo-100 bg-indigo-50/60 px-4 py-3.5 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-semibold text-indigo-800">
                    Jangan lupa menabung {{ format_rupiah($setting->daily_amount) }} hari ini
                </p>
                <form method="POST" action="{{ route('savings.today') }}" class="shrink-0">
                    @csrf
                    <input type="hidden" name="amount" value="{{ $setting->daily_amount }}">
                    <button type="submit" class="btn-primary w-full sm:w-auto">@icon('plus', 15) Catat sekarang</button>
                </form>
            </div>
        @endunless
    </div>

    {{-- Search --}}
    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('savings.index') }}" class="flex gap-2">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">@icon('search', 16)</span>
                <input type="search" name="q" value="{{ $keyword }}"
                       placeholder="Cari catatan / jumlah / tanggal" class="field-input pl-10">
            </div>
            <button type="submit" class="btn-primary">@icon('search', 15) Cari</button>
            @if ($keyword)
                <a href="{{ route('savings.index') }}" class="btn-outline">Reset</a>
            @endif
        </form>
        @if ($keyword)
            <p class="mt-3 text-xs font-medium text-slate-400">
                Hasil pencarian untuk "{{ $keyword }}" &middot; {{ $savings->total() }} data ditemukan.
            </p>
        @endif
    </div>

    {{-- History --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
                @icon('list', 16) Riwayat Menabung
            </h2>
            <span class="text-xs font-semibold text-slate-400">{{ $savings->total() }} setoran</span>
        </div>

        @if ($savings->isEmpty())
            <div class="px-5 py-14 text-center">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                    @icon($keyword ? 'search' : 'target', 24)
                </span>
                <p class="mt-4 text-sm font-bold text-slate-600">
                    {{ $keyword ? 'Setoran tidak ditemukan' : 'Belum ada setoran tabungan' }}
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    {{ $keyword ? 'Coba kata kunci lain.' : 'Mulai menabung dari sekarang, sedikit demi sedikit.' }}
                </p>
                @unless ($keyword)
                    <a href="{{ route('savings.create') }}" class="btn-soft mt-4">@icon('plus', 15) Catat setoran</a>
                @endunless
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach ($savings as $saving)
                    <li class="flex flex-wrap items-center gap-3 px-5 py-3.5 transition hover:bg-slate-50/70">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-indigo-50 text-indigo-600">
                            @icon('coins', 17)
                        </span>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-slate-800">
                                {{ $saving->note ?: 'Setoran tabungan' }}
                            </p>
                            <p class="flex items-center gap-1.5 text-xs text-slate-400">
                                @icon('calendar', 12)
                                {{ tanggal_indo($saving->date, true) }}
                                @if ($saving->date->isToday())
                                    <span class="badge ml-1 bg-emerald-50 text-emerald-600 normal-case">Hari ini</span>
                                @endif
                            </p>
                        </div>

                        <p class="shrink-0 text-sm font-extrabold text-indigo-600">+{{ format_rupiah($saving->amount) }}</p>

                        <div class="flex shrink-0 items-center gap-1">
                            <a href="{{ route('savings.show', $saving) }}"
                               class="btn-icon text-slate-400 hover:bg-slate-100 hover:text-slate-700 focus-visible:ring-slate-400"
                               title="Detail">@icon('eye', 16)</a>
                            <a href="{{ route('savings.edit', $saving) }}"
                               class="btn-icon text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 focus-visible:ring-indigo-500"
                               title="Edit">@icon('pencil', 16)</a>
                            <form method="POST" action="{{ route('savings.destroy', $saving) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus setoran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-icon text-slate-400 hover:bg-rose-50 hover:text-rose-600 focus-visible:ring-rose-500"
                                        title="Hapus">@icon('trash-2', 16)</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $savings->links() }}
            </div>
        @endif
    </div>
@endsection
