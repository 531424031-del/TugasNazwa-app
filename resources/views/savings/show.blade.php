@extends('layouts.app')

@section('title', 'Detail Setoran')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('savings.index') }}"
           class="mb-5 inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 transition hover:text-slate-900">
            @icon('arrow-left', 15) Kembali ke tabungan
        </a>

        <div class="card overflow-hidden">
            <div class="px-6 py-7 text-center sm:px-8">
                <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                    @icon('coins', 24)
                </span>
                <span class="badge mt-4 bg-indigo-50 text-indigo-700">Setoran Tabungan</span>
                <p class="mt-3 text-3xl font-extrabold tracking-tight text-indigo-600">
                    +{{ format_rupiah($saving->amount) }}
                </p>
                <p class="mt-1.5 text-sm font-semibold text-slate-500">
                    {{ $saving->note ?: 'Tanpa catatan' }}
                </p>
            </div>

            <dl class="divide-y divide-slate-100 border-t border-slate-100 text-sm">
                <div class="flex items-center justify-between gap-4 px-6 py-4">
                    <dt class="flex items-center gap-2 font-semibold text-slate-500">@icon('calendar', 15) Tanggal</dt>
                    <dd class="font-bold text-slate-800">{{ tanggal_indo($saving->date, true) }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 px-6 py-4">
                    <dt class="flex items-center gap-2 font-semibold text-slate-500">@icon('target', 15) Catatan</dt>
                    <dd class="text-right font-medium text-slate-700">{{ $saving->note ?: 'Tanpa catatan' }}</dd>
                </div>
                <div class="flex items-center justify-between gap-4 px-6 py-4">
                    <dt class="flex items-center gap-2 font-semibold text-slate-500">@icon('clock', 15) Dicatat</dt>
                    <dd class="text-xs text-slate-400">{{ $saving->created_at?->diffForHumans() ?? '-' }}</dd>
                </div>
            </dl>

            <div class="flex flex-col-reverse gap-2 border-t border-slate-100 bg-slate-50/60 px-6 py-4 sm:flex-row sm:justify-end">
                <form method="POST" action="{{ route('savings.destroy', $saving) }}"
                      onsubmit="return confirm('Yakin ingin menghapus setoran ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger w-full sm:w-auto">@icon('trash-2', 15) Hapus</button>
                </form>
                <a href="{{ route('savings.edit', $saving) }}" class="btn-outline w-full sm:w-auto">
                    @icon('pencil', 15) Edit setoran
                </a>
            </div>
        </div>
    </div>
@endsection
