<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Masuk') · Keuangan Pribadi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans">
    <div class="flex min-h-screen">
        <aside class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-violet-700 p-12 text-white lg:flex">
            <div class="pointer-events-none absolute -right-24 -top-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-20 h-80 w-80 rounded-full bg-violet-400/20 blur-3xl"></div>

            <a href="{{ route('home') }}" class="relative flex items-center gap-2.5">
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-white/15 backdrop-blur">@icon('wallet', 20)</span>
                <span class="text-base font-extrabold tracking-tight">KeuanganPribadi</span>
            </a>

            <div class="relative max-w-md">
                <p class="badge bg-white/15 text-white">Untuk mahasiswa</p>
                <h1 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight">
                    Atur uang saku harian tanpa ribet.
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-indigo-100">
                    Catat pemasukan dan pengeluaran, pantau saldo tersisa, serta kejar
                    target tabunganmu satu hari demi satu hari.
                </p>

                <ul class="mt-8 space-y-3.5 text-sm text-indigo-50">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-white/15">@icon('check-circle', 14)</span>
                        Ringkasan uang saku, pemasukan, pengeluaran, dan saldo dalam satu dashboard.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-white/15">@icon('check-circle', 14)</span>
                        Pengingat menabung harian lengkap dengan progress menuju target.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-white/15">@icon('check-circle', 14)</span>
                        Data tiap akun terpisah dan hanya bisa diakses oleh pemiliknya.
                    </li>
                </ul>
            </div>

            <p class="relative text-xs text-indigo-200">Sederhana, bersih, dan mudah dipahami.</p>
        </aside>

        <main class="flex w-full items-center justify-center px-4 py-10 sm:px-6 lg:w-1/2 lg:py-14">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="mb-8 flex items-center gap-2.5 lg:hidden">
                    <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">@icon('wallet', 18)</span>
                    <span class="text-[15px] font-extrabold tracking-tight text-slate-900">
                        Keuangan<span class="text-indigo-600">Pribadi</span>
                    </span>
                </a>

                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
