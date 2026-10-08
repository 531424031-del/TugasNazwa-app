@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <div class="card p-7 sm:p-8">
        <div class="text-center">
            <span class="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-indigo-50 text-indigo-600">
                @icon('user-plus', 22)
            </span>
            <h1 class="mt-4 text-xl font-extrabold tracking-tight text-slate-900">Buat akun baru</h1>
            <p class="mt-1.5 text-sm text-slate-500">Mulai atur uang saku dan tabunganmu hari ini.</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="mt-7 space-y-4">
            @csrf

            <div>
                <label for="name" class="field-label">Nama lengkap</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        @icon('user', 16)
                    </span>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                           autocomplete="name" autofocus placeholder="Contoh: Dinda Ayu"
                           class="field-input pl-10">
                </div>
                @error('name')
                    <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="field-label">Email</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        @icon('mail', 16)
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           autocomplete="email" placeholder="nama@kampus.ac.id"
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
                           autocomplete="new-password" placeholder="Minimal 8 karakter"
                           class="field-input pl-10">
                </div>
                @error('password')
                    <p class="field-error">@icon('alert-circle', 13) {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="field-label">Ulangi password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        @icon('lock', 16)
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           autocomplete="new-password" placeholder="Ketik ulang password"
                           class="field-input pl-10">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3">
                Buat akun @icon('arrow-right', 16)
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                Masuk di sini
            </a>
        </p>
    </div>
@endsection
