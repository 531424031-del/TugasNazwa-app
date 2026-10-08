@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('transactions.index') }}"
           class="mb-5 inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 transition hover:text-slate-900">
            @icon('arrow-left', 15) Kembali ke transaksi
        </a>

        <div class="card overflow-hidden">
            <div class="px-6 py-7 text-center sm:px-8">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                    @icon(category_icon($transaction->category), 24)
                </span>

                <span class="badge mt-4 {{ $transaction->type === 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                    {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                </span>

                <p class="mt-3 text-3xl font-extrabold tracking-tight {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-500' }}">
                    {{ $transaction->type === 'income' ? '+' : '−' }}{{ format_rupiah($transaction->amount) }}
                </p>
                <p class="mt-1.5 text-sm font-semibold text-slate-500">{{ $transaction->category }}</p>
            </div>

            <dl class="divide-y divide-slate-100 border-t border-slate-100 text-sm">
                <div class="flex items-center justify-between gap-4 px-6 py-4">
                    <dt class="flex items-center gap-2 font-semibold text-slate-500">@icon('calendar', 15) Tanggal</dt>
                    <dd class="font-bold text-slate-800">{{ tanggal_indo($transaction->date, true) }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 px-6 py-4">
                    <dt class="flex items-center gap-2 font-semibold text-slate-500">@icon('tag', 15) Kategori</dt>
                    <dd class="font-bold text-slate-800">{{ $transaction->category }}</dd>
                </div>
                <div class="flex items-start justify-between gap-4 px-6 py-4">
                    <dt class="flex shrink-0 items-center gap-2 font-semibold text-slate-500">@icon('receipt', 15) Keterangan</dt>
                    <dd class="text-right font-medium text-slate-700">
                        {{ $transaction->description ?: 'Tanpa keterangan' }}
                    </dd>
                </div>
                <div class="flex items-center justify-between gap-4 px-6 py-4">
                    <dt class="flex items-center gap-2 font-semibold text-slate-500">@icon('clock', 15) Dicatat</dt>
                    <dd class="text-xs text-slate-400">{{ $transaction->created_at?->diffForHumans() ?? '-' }}</dd>
                </div>
            </dl>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/60 px-6 py-4 sm:flex-row sm:justify-end">
                <form method="POST" action="{{ route('transactions.destroy', $transaction) }}"
                      onsubmit="return confirm('Yakin ingin menghapus transaksi ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger w-full sm:w-auto">@icon('trash-2', 15) Hapus</button>
                </form>
                <a href="{{ route('transactions.edit', $transaction) }}" class="btn-outline w-full sm:w-auto">
                    @icon('pencil', 15) Edit transaksi
                </a>
            </div>
        </div>
    </div>
@endsection
