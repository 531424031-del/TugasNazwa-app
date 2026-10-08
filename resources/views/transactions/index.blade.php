@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Transaksi</h1>
            <p class="mt-1 text-sm text-slate-500">Catat dan kelola pemasukan serta pengeluaranmu.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="btn-soft">
                @icon('plus', 15) Pemasukan
            </a>
            <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="btn-primary">
                @icon('plus', 15) Pengeluaran
            </a>
        </div>
    </div>

    {{-- Totals --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-2">
        <div class="card flex items-center gap-4 p-5">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-emerald-50 text-emerald-600">@icon('trending-up', 20)</span>
            <div class="min-w-0">
                <p class="text-[13px] font-semibold text-slate-500">Total Pemasukan</p>
                <p class="truncate text-xl font-extrabold tracking-tight text-emerald-600">{{ format_rupiah($totals['income']) }}</p>
            </div>
        </div>
        <div class="card flex items-center gap-4 p-5">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-rose-50 text-rose-500">@icon('trending-down', 20)</span>
            <div class="min-w-0">
                <p class="text-[13px] font-semibold text-slate-500">Total Pengeluaran</p>
                <p class="truncate text-xl font-extrabold tracking-tight text-rose-500">{{ format_rupiah($totals['expense']) }}</p>
            </div>
        </div>
    </div>

    {{-- Filter & search --}}
    <div class="card mb-6 p-4">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex w-fit gap-1 rounded-xl bg-slate-100 p-1">
                @foreach (['' => 'Semua', 'income' => 'Pemasukan', 'expense' => 'Pengeluaran'] as $key => $label)
                    <a href="{{ route('transactions.index', array_filter(['type' => $key, 'q' => $keyword])) }}"
                       class="rounded-lg px-3.5 py-1.5 text-sm font-bold transition {{ ($type ?? null) === $key ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('transactions.index') }}" class="flex gap-2">
                @if ($type)
                    <input type="hidden" name="type" value="{{ $type }}">
                @endif
                <div class="relative flex-1 lg:w-72">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">@icon('search', 16)</span>
                    <input type="search" name="q" value="{{ $keyword }}"
                           placeholder="Cari kategori / keterangan / tanggal"
                           class="field-input pl-10">
                </div>
                <button type="submit" class="btn-primary">@icon('search', 15) Cari</button>
                @if ($keyword)
                    <a href="{{ route('transactions.index', array_filter(['type' => $type])) }}" class="btn-outline">Reset</a>
                @endif
            </form>
        </div>

        @if ($keyword)
            <p class="mt-3 text-xs font-medium text-slate-400">
                Hasil pencarian untuk "{{ $keyword }}" &middot; {{ $transactions->total() }} data ditemukan.
            </p>
        @endif
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        @if ($transactions->isEmpty())
            <div class="px-5 py-14 text-center">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                    @icon($keyword ? 'search' : 'receipt', 24)
                </span>
                <p class="mt-4 text-sm font-bold text-slate-600">
                    {{ $keyword ? 'Transaksi tidak ditemukan' : 'Belum ada transaksi' }}
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    {{ $keyword ? 'Coba kata kunci lain atau ubah filter.' : 'Mulai catat pemasukan atau pengeluaranmu.' }}
                </p>
                @if (! $keyword)
                    <a href="{{ route('transactions.create') }}" class="btn-soft mt-4">@icon('plus', 15) Tambah transaksi</a>
                @endif
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <tr>
                            <th class="px-5 py-3.5 text-left">Tanggal</th>
                            <th class="px-5 py-3.5 text-left">Transaksi</th>
                            <th class="px-5 py-3.5 text-left">Jenis</th>
                            <th class="px-5 py-3.5 text-right">Jumlah</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($transactions as $transaction)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="whitespace-nowrap px-5 py-3.5 text-xs font-semibold text-slate-500">
                                    {{ tanggal_indo($transaction->date) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                                            @icon(category_icon($transaction->category), 15)
                                        </span>
                                        <div class="min-w-0">
                                            <p class="truncate font-bold text-slate-800">{{ $transaction->category }}</p>
                                            <p class="max-w-[220px] truncate text-xs text-slate-400">
                                                {{ $transaction->description ?: 'Tanpa keterangan' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="badge {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                                        {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-right font-extrabold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ $transaction->type === 'income' ? '+' : '−' }}{{ format_rupiah($transaction->amount) }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('transactions.show', $transaction) }}"
                                           class="btn-icon text-slate-400 hover:bg-slate-100 hover:text-slate-700 focus-visible:ring-slate-400"
                                           title="Detail">@icon('eye', 16)</a>
                                        <a href="{{ route('transactions.edit', $transaction) }}"
                                           class="btn-icon text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 focus-visible:ring-indigo-500"
                                           title="Edit">@icon('pencil', 16)</a>
                                        <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                                              onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn-icon text-slate-400 hover:bg-rose-50 hover:text-rose-600 focus-visible:ring-rose-500"
                                                    title="Hapus">@icon('trash-2', 16)</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
@endsection
