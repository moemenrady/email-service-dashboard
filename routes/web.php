<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\ForwarderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'domain.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Domains
    Route::post('/domains/switch', [DomainController::class, 'switchDomain'])->name('domains.switch');
    Route::get('/domain/dns', [DomainController::class, 'showDns'])->name('domains.dns');

    // Email Accounts Management (CRUD)
    Route::get('/emails', [EmailAccountController::class, 'index'])->name('emails.index');
    Route::post('/emails', [EmailAccountController::class, 'store'])->name('emails.store');
    Route::patch('/emails/{user}', [EmailAccountController::class, 'update'])->name('emails.update');
    Route::delete('/emails/{user}', [EmailAccountController::class, 'destroy'])->name('emails.destroy');

    // Forwarders Management (CRUD)
    Route::get('/forwarders', [ForwarderController::class, 'index'])->name('forwarders.index');
    Route::post('/forwarders', [ForwarderController::class, 'store'])->name('forwarders.store');
    Route::delete('/forwarders/{alias}', [ForwarderController::class, 'destroy'])->name('forwarders.destroy');

    // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
