<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SavingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
|
| Every record in this group is always loaded through the authenticated
| user's own relationship, so users can never reach each other's data.
|
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transactions -------------------------------------------------------
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Savings ------------------------------------------------------------
    // Static segments are registered before {saving} so they never collide.
    Route::get('/savings', [SavingController::class, 'index'])->name('savings.index');
    Route::get('/savings/settings', [SavingController::class, 'editSettings'])->name('savings.settings');
    Route::put('/savings/settings', [SavingController::class, 'updateSettings'])->name('savings.settings.update');
    Route::post('/savings/today', [SavingController::class, 'storeToday'])->name('savings.today');
    Route::get('/savings/create', [SavingController::class, 'create'])->name('savings.create');
    Route::post('/savings', [SavingController::class, 'store'])->name('savings.store');
    Route::get('/savings/{saving}', [SavingController::class, 'show'])->name('savings.show');
    Route::get('/savings/{saving}/edit', [SavingController::class, 'edit'])->name('savings.edit');
    Route::put('/savings/{saving}', [SavingController::class, 'update'])->name('savings.update');
    Route::delete('/savings/{saving}', [SavingController::class, 'destroy'])->name('savings.destroy');
});
