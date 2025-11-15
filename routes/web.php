<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientViewController;
use App\Http\Controllers\DebtViewController;
use App\Http\Controllers\PaymentViewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatementViewController;
use App\Http\Controllers\TotalAccountViewController; // This controller seems unused, consider removing if not needed.


// This route now redirects to the login page if not authenticated,
// otherwise it proceeds to the dashboard.
Route::get('/', function () {
    return redirect()->route('login'); // Redirect to the login page
})->middleware('guest'); // Only apply this redirection for guests

// This route defines the dashboard and requires authentication.
// It should be accessible only after a user logs in.
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // العملاء (Clients)
    Route::get('/clients', [ClientViewController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [ClientViewController::class, 'create'])->name('clients.create');
    Route::post('/clients', [ClientViewController::class, 'store'])->name('clients.store');
    Route::get('/clients/{id}', [ClientViewController::class, 'show'])->name('clients.show');
    Route::get('/clients/{id}/edit', [ClientViewController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{id}', [ClientViewController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [ClientViewController::class, 'destroy'])->name('clients.destroy');

    // الديون (Debts)
    Route::get('/debts', [DebtViewController::class, 'index'])->name('debts.index');
    Route::get('/debts/create', [DebtViewController::class, 'create'])->name('debts.create');
    Route::post('/debts', [DebtViewController::class, 'store'])->name('debts.store');
    Route::get('/debts/{id}', [DebtViewController::class, 'show'])->name('debts.show');
    Route::get('/debts/{id}/edit', [DebtViewController::class, 'edit'])->name('debts.edit');
    Route::put('/debts/{id}', [DebtViewController::class, 'update'])->name('debts.update');
    Route::delete('/debts/{id}', [DebtViewController::class, 'destroy'])->name('debts.destroy');
    Route::get('/debts/client/{client_id}', [DebtViewController::class, 'byClient'])->name('debts.byClient');

    // التسديدات (Payments)
    Route::get('/payments', [PaymentViewController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentViewController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentViewController::class, 'store'])->name('payments.store');
    Route::get('/payments/client/{client_id}', [PaymentViewController::class, 'byClient'])->name('payments.byClient');

    // الإشعارات (Notifications)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // كشف الحساب (Statement)
    Route::get('/clients/{id}/statement', [StatementViewController::class, 'show'])->name('clients.statement');

    // كل الحركات (All Transactions)
    Route::get('/transactions/all', [StatementViewController::class, 'allTransactions'])->name('transactions.all');
});

// Authentication Routes
require __DIR__ . '/auth.php';
