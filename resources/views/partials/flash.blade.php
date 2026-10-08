@if (session('success'))
    <div data-flash class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3.5">
        <span class="mt-0.5 shrink-0 text-emerald-500">@icon('check-circle', 18)</span>
        <p class="flex-1 text-sm font-medium leading-relaxed text-emerald-800">{{ session('success') }}</p>
        <button type="button" data-dismiss class="shrink-0 rounded-lg p-1 text-emerald-400 transition hover:bg-emerald-100 hover:text-emerald-700"
                aria-label="Tutup pesan">
            @icon('x', 15)
        </button>
    </div>
@endif

@if (session('error'))
    <div data-flash class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3.5">
        <span class="mt-0.5 shrink-0 text-rose-500">@icon('alert-circle', 18)</span>
        <p class="flex-1 text-sm font-medium leading-relaxed text-rose-800">{{ session('error') }}</p>
        <button type="button" data-dismiss class="shrink-0 rounded-lg p-1 text-rose-400 transition hover:bg-rose-100 hover:text-rose-700"
                aria-label="Tutup pesan">
            @icon('x', 15)
        </button>
    </div>
@endif
