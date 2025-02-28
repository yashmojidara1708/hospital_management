<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Auth::routes();

Route::any('/', [LoginController::class, 'showLoginForm'])->name('admin');
Route::any('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::any('/check/login', [LoginController::class, 'login']);

Route::any('/admin/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/home', [DashboardController::class, 'index'])->name('admin.home');
    // Specialities
    Route::get('/specialities', [App\Http\Controllers\Admin\SpecialitiesController::class, 'index'])->name('admin.specialities');
    Route::post('/specialitieslist', [App\Http\Controllers\Admin\SpecialitiesController::class, 'specialitieslist'])->name('admin.specialitieslist');
    Route::post('/specialities/save', [App\Http\Controllers\Admin\SpecialitiesController::class, 'save'])->name('save.specialities');
    Route::get('/specialities/edit', [App\Http\Controllers\Admin\SpecialitiesController::class, 'edit'])->name('edit.specialities');
    Route::post('/specialities/delete', [App\Http\Controllers\Admin\SpecialitiesController::class, 'delete'])->name('delete.specialities');
});
