<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Terjadi Kesalahan') · Keuangan Pribadi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-12 font-sans">
    <div class="w-full max-w-md text-center">
        <a href="/" class="mx-auto mb-8 flex w-fit items-center gap-2.5">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12V7H5a2 2 0 0 1 0-4h14v4"/>
                    <path d="M3 5v14a2 2 0 0 0 2 2h16v-5"/>
                    <path d="M18 12a2 2 0 0 0 0 4h4v-4Z"/>
                </svg>
            </span>
            <span class="text-base font-extrabold tracking-tight text-slate-900">
                Keuangan<span class="text-indigo-600">Pribadi</span>
            </span>
        </a>

        <div class="card p-8">
            <span class="mx-auto grid h-14 w-14 place-items-center rounded-2xl @yield('icon-bg', 'bg-indigo-50') @yield('icon-text', 'text-indigo-600')">
                @yield('icon')
            </span>

            <p class="mt-5 text-4xl font-extrabold tracking-tight text-slate-900">@yield('code')</p>
            <h1 class="mt-2 text-lg font-extrabold text-slate-800">@yield('heading')</h1>
            <p class="mt-2 text-sm leading-relaxed text-slate-500">@yield('message')</p>

            <div class="mt-7 flex flex-col gap-2 sm:flex-row sm:justify-center">
                @yield('actions')
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-400">Keuangan Pribadi &middot; Aplikasi pencatat keuangan harian mahasiswa.</p>
    </div>
</body>
</html>
