<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ReportController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('sales', SaleController::class)->only(['index', 'create', 'store', 'show']);

Route::get('journal', [JournalController::class, 'index'])->name('journal.index');
Route::get('journal/{journalEntry}', [JournalController::class, 'show'])->name('journal.show');

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');