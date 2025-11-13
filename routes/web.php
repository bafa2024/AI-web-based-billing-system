<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Client routes
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/create', [ClientController::class, 'create']);
    Route::post('/clients', [ClientController::class, 'store']);
    Route::get('/clients/{id}/edit', [ClientController::class, 'edit']);
    Route::put('/clients/{id}', [ClientController::class, 'update']);
    Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

    // Product routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Quote routes
    Route::get('/quotes', [QuoteController::class, 'index']);
    Route::get('/quotes/create', [QuoteController::class, 'create']);
    Route::post('/quotes', [QuoteController::class, 'store']);
    Route::get('/quotes/{id}', [QuoteController::class, 'show']);
    Route::get('/quotes/{id}/edit', [QuoteController::class, 'edit']);
    Route::put('/quotes/{id}', [QuoteController::class, 'update']);
    Route::delete('/quotes/{id}', [QuoteController::class, 'destroy']);
    Route::post('/quotes/{id}/convert-to-invoice', [QuoteController::class, 'convertToInvoice'])->name('quotes.convert-to-invoice');
    Route::get('/quotes/search/clients', [QuoteController::class, 'searchClients'])->name('quotes.search.clients');
    Route::get('/quotes/search/products', [QuoteController::class, 'searchProducts'])->name('quotes.search.products');

    // Invoice routes
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/create', [InvoiceController::class, 'create']);
    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit']);
    Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
    Route::delete('/invoices/{id}', [InvoiceController::class, 'destroy']);
    Route::get('/invoices/{id}/pdf', [InvoiceController::class, 'downloadPdf']);
    Route::post('/invoices/{id}/email', [InvoiceController::class, 'emailInvoice']);

    // Expense routes
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::get('/expenses/create', [ExpenseController::class, 'create']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'show']);
    Route::get('/expenses/{id}/edit', [ExpenseController::class, 'edit']);
    Route::put('/expenses/{id}', [ExpenseController::class, 'update']);
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy']);

    // Report routes
    Route::get('/reports', [ReportController::class, 'index']);
    Route::get('/reports/export/clients', [ReportController::class, 'exportClients']);
    Route::get('/reports/export/invoices', [ReportController::class, 'exportInvoices']);
    Route::get('/reports/export/profit-loss', [ReportController::class, 'exportProfitLoss']);
    Route::get('/reports/export/profit-loss-pdf', [ReportController::class, 'exportProfitLossPdf']);
    Route::post('/reports/email/profit-loss', [ReportController::class, 'emailProfitLossReport']);
});
