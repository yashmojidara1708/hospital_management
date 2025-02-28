<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
Auth::routes();

Route::any('/', [LoginController::class, 'showLoginForm'])->name('admin');
Route::any('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::any('/check/login', [LoginController::class, 'login']);


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');
});
