<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\ForwarderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Entrance: strictly redirect to login if guest, or role-based dashboard if authenticated
Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('emails.index');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/clients', [AdminController::class, 'storeClient'])->name('clients.store');
    Route::patch('/clients/{user}', [AdminController::class, 'updateClient'])->name('clients.update');
    Route::delete('/clients/{user}', [AdminController::class, 'destroyClient'])->name('clients.destroy');
    Route::post('/domains', [AdminController::class, 'storeDomain'])->name('domains.store');
    Route::delete('/domains/{domain}', [AdminController::class, 'deleteDomain'])->name('domains.destroy');
    Route::post('/domains/switch', [AdminController::class, 'switchDomain'])->name('domains.switch');
});

// Authenticated Client & Management Routes (Scoped to authorized domain)
Route::middleware(['auth', 'domain.auth'])->group(function () {
    // Client Dashboard redirects to emails management
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('emails.index');
    })->name('dashboard');

    // Domains
    Route::post('/domains/switch', [DomainController::class, 'switchDomain'])->name('domains.switch');
    Route::get('/domain/dns', [DomainController::class, 'showDns'])->name('domains.dns');

    // Email Accounts Management (CRUD) - Primary interface for clients
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
