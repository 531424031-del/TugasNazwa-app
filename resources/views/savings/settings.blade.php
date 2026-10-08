@extends('layouts.app')

@section('title', 'Target Tabungan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('savings.index') }}"
           class="mb-5 inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 transition hover:text-slate-900">
            @icon('arrow-left', 15) Kembali ke tabungan
        </a>

        <div class="card p-6 sm:p-7">
            <div class="flex items-start gap-3.5">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-amber-50 text-amber-600">
                    @icon('settings', 20)
                </span>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900">Target Tabungan</h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Tentukan target akhir dan nominal menabung harianmu.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('savings.settings.update') }}" class="mt-7 space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="target_amount" class="field-label">Target tabungan (Rp)</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input id="target_amount" type="number" name="target_amount" min="1" step="1" required
                               value="{{ old('target_amount', $setting->target_amount ? (int) $setting->target_amount : '') }}"
                               placeholder="1000000" class="field-input pl-11">
                    </div>
                    @error('target_amount')
                        <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-slate-400">Contoh: 1000000 berarti Rp1.000.000.</p>
                </div>

                <div>
                    <label for="daily_amount" class="field-label">Target menabung per hari (Rp)</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                        <input id="daily_amount" type="number" name="daily_amount" min="1" step="1" required
                               value="{{ old('daily_amount', $setting->daily_amount ? (int) $setting->daily_amount : '') }}"
                               placeholder="5000" class="field-input pl-11">
                    </div>
                    @error('daily_amount')
                        <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-slate-400">
                        Pengingat di dashboard akan memakai nominal ini.
                    </p>
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('savings.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">@icon('save', 16) Simpan Target</button>
                </div>
            </form>
        </div>

        @php
            $hintDays = $setting->daily_amount > 0
                ? (int) ceil(max(0, (float) $setting->target_amount) / (float) $setting->daily_amount)
                : 0;
        @endphp
        <div class="card mt-5 flex items-start gap-3.5 border-indigo-100 bg-indigo-50/50 p-5">
            <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-indigo-100 text-indigo-600">
                @icon('info', 17)
            </span>
            <div class="text-sm text-slate-600">
                <p class="font-bold text-slate-800">Perkiraan waktu mencapai target</p>
                <p class="mt-0.5">
                    Dengan menabung {{ format_rupiah($setting->daily_amount) }} setiap hari, kamu butuh sekitar
                    <span class="font-bold text-indigo-700">{{ $hintDays }} hari</span>
                    untuk mengumpulkan {{ format_rupiah($setting->target_amount) }}.
                </p>
            </div>
        </div>
    </div>
@endsection
