@extends('layouts.app')

@section('title', $transaction ? 'Edit Transaksi' : 'Tambah Transaksi')

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('transactions.index') }}"
           class="mb-5 inline-flex items-center gap-1.5 text-sm font-bold text-slate-500 transition hover:text-slate-900">
            @icon('arrow-left', 15) Kembali ke transaksi
        </a>

        <div class="card p-6 sm:p-7">
            <div class="flex items-start gap-3.5">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                    @icon('receipt', 20)
                </span>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tight text-slate-900">
                        {{ $transaction ? 'Edit Transaksi' : 'Tambah Transaksi' }}
                    </h1>
                    <p class="mt-1 text-sm text-slate-500">
                        Pilih jenis, kategori, lalu isi jumlah dan tanggal transaksi.
                    </p>
                </div>
            </div>

            @php
                $selectedType = old('type', $transaction?->type ?? \App\Models\Transaction::TYPE_EXPENSE);
            @endphp

            <form method="POST"
                  action="{{ $transaction ? route('transactions.update', $transaction) : route('transactions.store') }}"
                  class="mt-7 space-y-5">
                @csrf
                @if ($transaction)
                    @method('PUT')
                @endif

                {{-- Type --}}
                <fieldset>
                    <legend class="field-label">Jenis transaksi</legend>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 transition has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50/70 has-[:checked]:ring-2 has-[:checked]:ring-indigo-500/15 border-slate-200 hover:border-slate-300">
                            <input type="radio" name="type" value="income"
                                   class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                   {{ $selectedType === 'income' ? 'checked' : '' }}>
                            <span class="grid h-8 w-8 place-items-center rounded-lg bg-emerald-100 text-emerald-600">@icon('trending-up', 16)</span>
                            <span class="text-sm font-bold text-slate-700">Pemasukan</span>
                        </label>

                        <label
                            class="flex cursor-pointer items-center gap-3 rounded-xl border px-4 py-3 transition has-[:checked]:border-indigo-400 has-[:checked]:bg-indigo-50/70 has-[:checked]:ring-2 has-[:checked]:ring-indigo-500/15 border-slate-200 hover:border-slate-300">
                            <input type="radio" name="type" value="expense"
                                   class="h-4 w-4 border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                   {{ $selectedType === 'expense' ? 'checked' : '' }}>
                            <span class="grid h-8 w-8 place-items-center rounded-lg bg-rose-100 text-rose-500">@icon('trending-down', 16)</span>
                            <span class="text-sm font-bold text-slate-700">Pengeluaran</span>
                        </label>
                    </div>
                    @error('type')
                        <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                    @enderror
                </fieldset>

                {{-- Category --}}
                <div>
                    <label for="category" class="field-label">Kategori</label>
                    <select id="category" name="category" required class="field-input">
                        <option value="">Pilih kategori</option>
                    </select>
                    @error('category')
                        <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                    @enderror
                </div>

                {{-- Amount & date --}}
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="amount" class="field-label">Jumlah (Rp)</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input id="amount" type="number" name="amount" min="1" step="1" required
                                   value="{{ old('amount', $transaction?->amount ? (int) $transaction->amount : '') }}"
                                   placeholder="50000" class="field-input pl-11">
                        </div>
                        @error('amount')
                            <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="field-label">Tanggal transaksi</label>
                        <input id="date" type="date" name="date" required
                               value="{{ old('date', $transaction?->date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                               class="field-input">
                        @error('date')
                            <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="field-label">Keterangan <span class="font-medium text-slate-400">(opsional)</span></label>
                    <input id="description" type="text" name="description" maxlength="255"
                           value="{{ old('description', $transaction?->description) }}"
                           placeholder="Contoh: Makan siang di kantin kampus"
                           class="field-input">
                    @error('description')
                        <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-2 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('transactions.index') }}" class="btn-outline">Batal</a>
                    <button type="submit" class="btn-primary">
                        @icon('save', 16) {{ $transaction ? 'Simpan Perubahan' : 'Simpan Transaksi' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const categories = @json([
                'income' => \App\Models\Transaction::INCOME_CATEGORIES,
                'expense' => \App\Models\Transaction::EXPENSE_CATEGORIES,
            ]);
            const select = document.getElementById('category');
            const preset = @json(old('category', $transaction?->category));
            const radios = document.querySelectorAll('input[name="type"]');

            function render() {
                const type = (document.querySelector('input[name="type"]:checked') || {}).value || 'expense';
                const list = categories[type] || [];
                const selected = list.includes(preset) ? preset : '';

                select.innerHTML = '<option value="">Pilih kategori</option>' +
                    list.map(function (c) {
                        return '<option value="' + c + '"' + (c === selected ? ' selected' : '') + '>' + c + '</option>';
                    }).join('');
            }

            radios.forEach(function (radio) {
                radio.addEventListener('change', render);
            });

            render();
        })();
    </script>
@endpush
