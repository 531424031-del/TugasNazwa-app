@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <div class="card p-7 sm:p-8">
        <div class="text-center">
            <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                @icon('log-in', 22)
            </span>
            <h1 class="mt-4 text-xl font-extrabold tracking-tight text-slate-900">Selamat datang kembali</h1>
            <p class="mt-1.5 text-sm text-slate-500">Masuk untuk melanjutkan mencatat keuanganmu.</p>
        </div>

        <form method="POST" action="{{ route('login.store') }}" class="mt-7 space-y-4">
            @csrf

            <div>
                <label for="email" class="field-label">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        @icon('mail', 16)
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           autocomplete="email" autofocus placeholder="nama@kampus.ac.id"
                           class="field-input pl-10">
                </div>
                @error('email')
                    <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="field-label">Password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        @icon('lock', 16)
                    </span>
                    <input id="password" type="password" name="password" required
                           autocomplete="current-password" placeholder="Minimal 8 karakter"
                           class="field-input pl-10">
                </div>
                @error('password')
                    <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                @enderror
            </div>

            <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1"
                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                Ingat saya di perangkat ini
            </label>

            <button type="submit" class="btn-primary w-full py-3">
                Masuk @icon('arrow-right', 16)
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                Daftar sekarang
            </a>
        </p>
    </div>
@endsection
