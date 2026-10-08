<?php

use App\Models\Transaction;
use App\Support\Icons;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\HtmlString;

if (! function_exists('format_rupiah')) {
    /**
     * Format an amount as Indonesian Rupiah, e.g. 15000 -> "Rp15.000".
     */
    function format_rupiah($amount): string
    {
        $value = (float) $amount;
        $formatted = number_format($value, 0, ',', '.');

        return ($value < 0 ? '-Rp' : 'Rp').ltrim($formatted, '-');
    }
}

if (! function_exists('transaction_categories')) {
    /**
     * Categories available for a transaction type.
     *
     * @return list<string>
     */
    function transaction_categories(string $type): array
    {
        return Transaction::categoriesFor($type);
    }
}

if (! function_exists('icon')) {
    /**
     * Render an inline SVG icon that inherits the current text color.
     */
    function icon(string $name, int $size = 20, string $class = ''): HtmlString
    {
        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="%d" height="%d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="%s" aria-hidden="true">%s</svg>',
            $size,
            $size,
            e($class),
            Icons::path($name)
        );

        return new HtmlString($svg);
    }
}

if (! function_exists('category_icon')) {
    /** The icon that best represents a transaction category. */
    function category_icon(string $category): string
    {
        return Icons::category($category);
    }
}

if (! function_exists('tanggal_indo')) {
    /**
     * Format a date as a long Indonesian date, e.g. "9 Oktober 2026".
     */
    function tanggal_indo($date, bool $withDay = false): string
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        $dt = $date instanceof CarbonInterface ? $date : Carbon::parse($date);

        $formatted = $dt->day.' '.$months[$dt->month].' '.$dt->year;

        return $withDay ? $days[$dt->dayOfWeek].', '.$formatted : $formatted;
    }
}
