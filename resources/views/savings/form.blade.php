@extends('layouts.app')

@section('title', $saving ? 'Edit Setoran' : 'Catat Setoran')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('savings.index') }}"
           class="mb-5 inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 transition hover:text-slate-900">
            @icon('arrow-left', 15) Kembali ke tabungan
        </a>

        <div class="card p-6 sm:p-7">
            <div class="flex items-start gap-3.5">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                    @icon('target', 20)
                </span>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900">
                        {{ $saving ? 'Edit Setoran Tabungan' : 'Catat Setoran Tabungan' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Isi jumlah setoran dan tanggal menabungmu.
                    </p>
                </div>
            </div>

            <form method="POST"
                  action="{{ $saving ? route('savings.update', $saving) : route('savings.store') }}"
                  class="mt-7 space-y-5">
                @csrf
                @if ($saving)
                    @method('PUT')
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="amount" class="field-label">Jumlah setoran (Rp)</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input id="amount" type="number" name="amount" min="1" step="1" required
                                   value="{{ old('amount', $saving?->amount ? (int) $saving->amount : ($defaultAmount ?: '')) }}"
                                   placeholder="5000" class="field-input pl-11">
                        </div>
                        @error('amount')
                            <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                        @enderror
                        @unless ($saving)
                            <p class="mt-1.5 text-xs text-slate-400">
                                Target harianmu {{ format_rupiah($defaultAmount) }}.
                            </p>
                        @endunless
                    </div>

                    <div>
                        <label for="date" class="field-label">Tanggal menabung</label>
                        <input id="date" type="date" name="date" required
                               value="{{ old('date', $saving?->date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="field-input">
                        @error('date')
                            <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="note" class="field-label">Catatan <span class="font-medium text-slate-400">(opsional)</span></label>
                    <input id="note" type="text" name="note" maxlength="255"
                           value="{{ old('note', $saving?->note) }}"
                           placeholder="Contoh: Nabung dari uang saku mingguan"
                           class="field-input">
                    @error('note')
                        <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('savings.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">
                        @icon('save', 16) {{ $saving ? 'Simpan Perubahan' : 'Simpan Setoran' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
