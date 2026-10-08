<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $today = now()->toDateString();

        $transactions = $user->transactions();

        $totalIncome = (float) (clone $transactions)
            ->where('type', Transaction::TYPE_INCOME)
            ->sum('amount');

        $totalExpense = (float) (clone $transactions)
            ->where('type', Transaction::TYPE_EXPENSE)
            ->sum('amount');

        $allowance = (float) (clone $transactions)
            ->where('type', Transaction::TYPE_INCOME)
            ->where('category', Transaction::CATEGORY_ALLOWANCE)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $totalSavings = (float) $user->savings()->sum('amount');
        $setting = $user->resolveSavingSetting();

        $target = (float) $setting->target_amount;
        $dailyTarget = (float) $setting->daily_amount;

        $progress = $target > 0 ? min(100, ($totalSavings / $target) * 100) : 0;
        $remaining = max(0, $target - $totalSavings);

        $todayQuery = $user->savings()->whereDate('date', $today);
        $savedToday = (float) (clone $todayQuery)->sum('amount');
        $hasSavedToday = (clone $todayQuery)->exists();

        $recentTransactions = $user->transactions()
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('dashboard', [
            'allowance' => $allowance,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'totalSavings' => $totalSavings,
            'setting' => $setting,
            'target' => $target,
            'dailyTarget' => $dailyTarget,
            'progress' => $progress,
            'remaining' => $remaining,
            'hasSavedToday' => $hasSavedToday,
            'savedToday' => $savedToday,
            'recentTransactions' => $recentTransactions,
            'today' => $today,
        ]);
    }
}
