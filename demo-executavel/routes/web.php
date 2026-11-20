<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinancialPeriodController;
use App\Http\Controllers\PaymentLotController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\UnpaidBillController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login'));

Route::middleware('guest')->group(function (): void {
    Route::get('/entrar', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/entrar', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'master'])->group(function (): void {
    Route::get('/painel', DashboardController::class)->name('dashboard');
    Route::get('/consultores', [ConsultantController::class, 'index'])->name('consultants.index');
    Route::get('/producoes', [ProductionController::class, 'index'])->name('productions.index');
    Route::get('/boletos-pendentes', [UnpaidBillController::class, 'index'])->name('unpaid-bills.index');
    Route::get('/competencias', [FinancialPeriodController::class, 'index'])->name('periods.index');
    Route::get('/competencias/{period}', [FinancialPeriodController::class, 'show'])->name('periods.show');
    Route::get('/lotes/{lot}', [PaymentLotController::class, 'show'])->name('lots.show');
    Route::post('/lotes/{lot}/fechar', [PaymentLotController::class, 'close'])->name('lots.close');
});

Route::post('/sair', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
