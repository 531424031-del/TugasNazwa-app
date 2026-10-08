@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Page header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-slate-400">
                @icon('calendar', 13) {{ tanggal_indo(now(), true) }}
            </p>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-900">
                Halo, {{ auth()->user()->name }}
            </h1>
            <p class="mt-1 text-sm text-slate-500">Berikut ringkasan keuanganmu hari ini.</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="btn-primary shrink-0">
            @icon('plus', 16) Tambah Transaksi
        </a>
    </div>

    {{-- Daily savings reminder --}}
    @if ($hasSavedToday)
        <div class="card mb-6 flex flex-col gap-4 border-emerald-200/70 bg-emerald-50/70 p-5 sm:flex-row sm:items-center">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-emerald-100 text-emerald-600">
                @icon('calendar-check', 20)
            </span>
            <div class="flex-1">
                <p class="text-sm font-extrabold text-emerald-900">Menabung hari ini sudah dicatat</p>
                <p class="mt-0.5 text-sm text-emerald-700">
                    Setoranmu hari ini {{ format_rupiah($savedToday) }}. Pertahankan ya!
                </p>
            </div>
            <a href="{{ route('savings.index') }}" class="btn-outline shrink-0 border-emerald-200 bg-white text-emerald-700">
                Riwayat tabungan
            </a>
        </div>
    @else
        <div class="card mb-6 flex flex-col gap-4 border-indigo-200/70 bg-gradient-to-br from-indigo-50 to-violet-50 p-5 sm:flex-row sm:items-center">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">
                @icon('sparkles', 20)
            </span>
            <div class="flex-1">
                <p class="text-sm font-extrabold text-slate-900">
                    Jangan lupa menabung {{ format_rupiah($dailyTarget) }} hari ini
                </p>
                <p class="mt-0.5 text-sm text-slate-600">
                    Target tabunganmu {{ format_rupiah($target) }}. Sedikit demi sedikit, lama-lama jadi gunung.
                </p>
            </div>
            <form method="POST" action="{{ route('savings.today') }}" class="shrink-0">
                @csrf
                <input type="hidden" name="amount" value="{{ $dailyTarget }}">
                <button type="submit" class="btn-primary w-full sm:w-auto">
                    @icon('plus', 16) Catat tabungan hari ini
                </button>
            </form>
        </div>
    @endif

    {{-- Summary cards --}}
    @php
        $cards = [
            ['label' => 'Uang Saku', 'value' => $allowance, 'icon' => 'wallet', 'iconBg' => 'bg-indigo-50', 'iconText' => 'text-indigo-600', 'hint' => 'Uang saku tercatat', 'highlight' => false],
            ['label' => 'Total Pemasukan', 'value' => $totalIncome, 'icon' => 'trending-up', 'iconBg' => 'bg-emerald-50', 'iconText' => 'text-emerald-600', 'hint' => 'Dari semua pemasukan', 'highlight' => false],
            ['label' => 'Total Pengeluaran', 'value' => $totalExpense, 'icon' => 'trending-down', 'iconBg' => 'bg-rose-50', 'iconText' => 'text-rose-500', 'hint' => 'Dari semua pengeluaran', 'highlight' => false],
            ['label' => 'Saldo Tersisa', 'value' => $balance, 'icon' => 'coins', 'iconBg' => 'bg-sky-50', 'iconText' => 'text-sky-600', 'hint' => 'Pemasukan dikurangi pengeluaran', 'highlight' => true],
            ['label' => 'Jumlah Tabungan', 'value' => $totalSavings, 'icon' => 'target', 'iconBg' => 'bg-amber-50', 'iconText' => 'text-amber-600', 'hint' => 'Dari seluruh setoran', 'highlight' => false],
        ];
    @endphp

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        @foreach ($cards as $card)
            <div class="card overflow-hidden p-5 {{ $card['highlight'] ? 'border-transparent bg-gradient-to-br from-indigo-600 to-violet-600 shadow-pop' : '' }}">
                <span class="grid h-10 w-10 place-items-center rounded-xl {{ $card['highlight'] ? 'bg-white/20 text-white' : $card['iconBg'] . ' ' . $card['iconText'] }}">
                    @icon($card['icon'], 19)
                </span>
                <p class="mt-4 text-[13px] font-semibold {{ $card['highlight'] ? 'text-indigo-100' : 'text-slate-500' }}">
                    {{ $card['label'] }}
                </p>
                <p class="mt-1 truncate text-xl font-extrabold tracking-tight {{ $card['highlight'] ? 'text-white' : 'text-slate-900' }}">
                    {{ format_rupiah($card['value']) }}
                </p>
                <p class="mt-1.5 text-[11px] font-medium {{ $card['highlight'] ? 'text-indigo-200' : 'text-slate-400' }}">
                    {{ $card['hint'] }}
                </p>
            </div>
        @endforeach
    </div>

    {{-- Savings progress + quick actions --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <p class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
                        @icon('target', 16) Progress Menuju Target
                    </p>
                    <p class="mt-1 text-xs text-slate-400">
                        {{ $hasSavedToday ? 'Kamu sudah menabung hari ini.' : 'Belum menabung hari ini.' }}
                    </p>
                </div>
                <span class="badge bg-indigo-50 text-indigo-700">{{ number_format($progress, 0) }}%</span>
            </div>

            <div class="mt-5 flex items-end gap-3">
                <p class="text-3xl font-extrabold tracking-tight text-slate-900">{{ format_rupiah($totalSavings) }}</p>
                <p class="pb-1 text-sm font-semibold text-slate-400">/ {{ format_rupiah($target) }}</p>
            </div>

            <div class="mt-3 progress-track" role="progressbar"
                 aria-valuenow="{{ number_format($progress, 0) }}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar bg-gradient-to-r from-indigo-500 to-violet-500"
                     style="width: {{ number_format($progress, 1) }}%"></div>
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-2 text-xs font-medium text-slate-500">
                <span class="flex items-center gap-1.5 text-emerald-600">@icon('check-circle', 13) Terkumpul {{ format_rupiah($totalSavings) }}</span>
                <span class="text-slate-400">Sisa {{ format_rupiah($remaining) }}</span>
            </div>

            <div class="mt-5 flex flex-wrap gap-2 border-t border-slate-100 pt-4">
                <a href="{{ route('savings.index') }}" class="btn-soft">@icon('list', 15) Riwayat tabungan</a>
                <a href="{{ route('savings.settings') }}" class="btn-outline">@icon('settings', 15) Ubah target</a>
            </div>
        </div>

        <div class="card p-6">
            <p class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
                @icon('sparkles', 16) Aksi Cepat
            </p>
            <div class="mt-4 flex flex-col gap-2.5">
                <a href="{{ route('transactions.create', ['type' => 'income']) }}"
                   class="group flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/70 px-3.5 py-3 transition hover:border-emerald-200 hover:bg-emerald-50">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-emerald-100 text-emerald-600">@icon('trending-up', 16)</span>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-emerald-700">Catat pemasukan</span>
                </a>
                <a href="{{ route('transactions.create', ['type' => 'expense']) }}"
                   class="group flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/70 px-3.5 py-3 transition hover:border-rose-200 hover:bg-rose-50">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-rose-100 text-rose-500">@icon('trending-down', 16)</span>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-rose-700">Catat pengeluaran</span>
                </a>
                <a href="{{ route('savings.create') }}"
                   class="group flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/70 px-3.5 py-3 transition hover:border-indigo-200 hover:bg-indigo-50">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-indigo-100 text-indigo-600">@icon('target', 16)</span>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-700">Catat setoran tabungan</span>
                </a>
                <a href="{{ route('savings.settings') }}"
                   class="group flex items-center gap-3 rounded-xl border border-slate-100 bg-slate-50/70 px-3.5 py-3 transition hover:border-amber-200 hover:bg-amber-50">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-amber-100 text-amber-600">@icon('settings', 16)</span>
                    <span class="text-sm font-bold text-slate-700 group-hover:text-amber-700">Atur target menabung</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent transactions --}}
    <div class="card mt-6">
        <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
            <h2 class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
                @icon('receipt', 16) Transaksi Terbaru
            </h2>
            <a href="{{ route('transactions.index') }}" class="flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                Lihat semua @icon('chevron-right', 13)
            </a>
        </div>

        @if ($recentTransactions->isEmpty())
            <div class="px-5 py-12 text-center">
                <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                    @icon('receipt', 22)
                </span>
                <p class="mt-3 text-sm font-bold text-slate-600">Belum ada transaksi</p>
                <p class="mt-1 text-xs text-slate-400">Mulai catat pemasukan atau pengeluaranmu.</p>
                <a href="{{ route('transactions.create') }}" class="btn-soft mt-4">@icon('plus', 15) Tambah transaksi</a>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach ($recentTransactions as $transaction)
                    <li>
                        <a href="{{ route('transactions.show', $transaction) }}"
                           class="flex items-center gap-3.5 px-5 py-3.5 transition hover:bg-slate-50">
                            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                                @icon(category_icon($transaction->category), 17)
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-slate-800">{{ $transaction->category }}</p>
                                <p class="truncate text-xs text-slate-400">
                                    {{ $transaction->description ?: 'Tanpa keterangan' }} &middot; {{ tanggal_indo($transaction->date) }}
                                </p>
                            </div>
                            <p class="shrink-0 text-sm font-extrabold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $transaction->type === 'income' ? '+' : '−' }}{{ format_rupiah($transaction->amount) }}
                            </p>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
