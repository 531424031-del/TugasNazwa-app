<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Keuangan Pribadi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen font-sans">
    @php
        $navItems = [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard'],
            ['route' => 'transactions.index', 'label' => 'Transaksi', 'icon' => 'receipt'],
            ['route' => 'savings.index', 'label' => 'Tabungan', 'icon' => 'target'],
        ];
        $currentUser = auth()->user();
    @endphp

    <header class="sticky top-0 z-40 border-b border-slate-200/70 bg-white/85 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-3 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex shrink-0 items-center gap-2.5">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-600 text-white shadow-md shadow-indigo-600/30">
                    @icon('wallet', 18)
                </span>
                <span class="text-[15px] font-extrabold tracking-tight text-slate-900">
                    Keuangan<span class="text-indigo-600">Pribadi</span>
                </span>
            </a>

            <nav class="hidden items-center gap-1 md:flex">
                @foreach ($navItems as $item)
                    @if (request()->routeIs($item['route']))
                        <a href="{{ route($item['route']) }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-indigo-50 px-3.5 py-2 text-sm font-semibold text-indigo-700">
                            @icon($item['icon'], 17) {{ $item['label'] }}
                        </a>
                    @else
                        <a href="{{ route($item['route']) }}"
                           class="inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                            @icon($item['icon'], 17) {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <div class="hidden text-right leading-tight sm:block">
                    <p class="max-w-[150px] truncate text-sm font-bold text-slate-900">{{ $currentUser->name }}</p>
                    <p class="max-w-[150px] truncate text-[11px] text-slate-400">{{ $currentUser->email }}</p>
                </div>
                <span class="hidden h-9 w-9 shrink-0 place-items-center rounded-full bg-indigo-100 text-sm font-extrabold text-indigo-700 sm:grid">
                    {{ strtoupper(mb_substr($currentUser->name, 0, 1)) }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-outline px-3" title="Keluar dari akun">
                        @icon('log-out', 16)
                        <span class="hidden lg:inline">Keluar</span>
                    </button>
                </form>

                <button type="button" id="menu-toggle" class="btn-icon text-slate-500 hover:bg-slate-100 md:hidden"
                        aria-label="Buka menu" aria-expanded="false">
                    @icon('menu', 20)
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden border-t border-slate-100 bg-white px-4 py-3 md:hidden">
            <div class="flex flex-col gap-1">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="inline-flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs($item['route']) ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                        @icon($item['icon'], 17) {{ $item['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('savings.settings') }}"
                   class="inline-flex items-center gap-2.5 rounded-xl px-3 py-2.5 text-sm font-semibold {{ request()->routeIs('savings.settings') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    @icon('settings', 17) Target Tabungan
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="mx-auto max-w-6xl px-4 pb-10 text-center text-xs text-slate-400 sm:px-6 lg:px-8">
        Keuangan Pribadi &middot; Catat uang saku, transaksi, dan tabungan harianmu.
    </footer>

    <script>
        (function () {
            const toggle = document.getElementById('menu-toggle');
            const menu = document.getElementById('mobile-menu');
            if (toggle && menu) {
                toggle.addEventListener('click', function () {
                    const hidden = menu.classList.toggle('hidden');
                    toggle.setAttribute('aria-expanded', String(!hidden));
                });
            }

            document.querySelectorAll('[data-dismiss]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const flash = button.closest('[data-flash]');
                    if (flash) flash.remove();
                });
            });

            window.setTimeout(function () {
                document.querySelectorAll('[data-flash]').forEach(function (flash) {
                    flash.style.transition = 'opacity .4s ease';
                    flash.style.opacity = '0';
                    window.setTimeout(function () { flash.remove(); }, 400);
                });
            }, 6000);
        })();
    </script>

    @stack('scripts')
</body>
</html>
