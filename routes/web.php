<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\EmailAccountController;
use App\Http\Controllers\ForwarderController;
use App\Http\Controllers\ProfileController;
use App\Services\DocumentPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Entrance: strictly redirect to login if guest, or role-based dashboard if authenticated
Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    return auth()->user()->isAdmin()
        ? redirect()->route('admin.dashboard')
        : redirect()->route('emails.index');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/activities', [AdminController::class, 'activities'])->name('activities.index');
    Route::get('/activities/export', [AdminController::class, 'exportActivitiesCsv'])->name('activities.export');
    Route::post('/clients', [AdminController::class, 'storeClient'])->name('clients.store');
    Route::patch('/clients/{user}', [AdminController::class, 'updateClient'])->name('clients.update');
    Route::delete('/clients/{user}', [AdminController::class, 'destroyClient'])->name('clients.destroy');
    Route::get('/clients/{user}/export-pdf', [AdminController::class, 'exportClientPdf'])->name('clients.export-pdf');
    Route::post('/domains', [AdminController::class, 'storeDomain'])->name('domains.store');
    Route::delete('/domains/{domain}', [AdminController::class, 'deleteDomain'])->name('domains.destroy');
    Route::post('/domains/switch', [AdminController::class, 'switchDomain'])->name('domains.switch');
});

// Authenticated Client & Management Routes (Scoped to authorized domain)
Route::middleware(['auth', 'domain.auth', 'track.activity'])->group(function () {
    // Client Dashboard redirects to emails management
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('emails.index');
    })->name('dashboard');

    // Setup & User Guide
    Route::get('/guide', [EmailAccountController::class, 'guide'])->name('emails.guide');
    Route::get('/emails/guide', [EmailAccountController::class, 'guide']);

    // Domains
    Route::post('/domains/switch', [DomainController::class, 'switchDomain'])->name('domains.switch');
    Route::get('/domain/dns', [DomainController::class, 'showDns'])->name('domains.dns');

    // Email Accounts Management (CRUD) & PDF Exports
    Route::get('/emails', [EmailAccountController::class, 'index'])->name('emails.index');
    Route::post('/emails', [EmailAccountController::class, 'store'])->name('emails.store');
    Route::get('/emails/export-all-pdf', [EmailAccountController::class, 'exportAllPdf'])->name('emails.export-all-pdf');
    Route::match(['GET', 'POST'], '/emails/{user}/export-pdf', [EmailAccountController::class, 'exportSinglePdf'])->name('emails.export-single-pdf');
    Route::post('/emails/{user}/log-share', [EmailAccountController::class, 'logShare'])->name('emails.log-share');
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

// Official Moemen Team Handover Documents (Arabic & English)
Route::get('/documents/handover/ar', function (Request $request) {
    $asDownload = $request->query('download') === '1';

    return DocumentPdfService::render(
        'documents.handover-ar',
        ['isPdfMode' => true],
        'Moemen-Team-Cloud-Email-Handover-AR.pdf',
        $asDownload
    );
})->name('documents.handover.ar');

Route::get('/documents/handover/en', function (Request $request) {
    $asDownload = $request->query('download') === '1';

    return DocumentPdfService::render(
        'documents.handover-en',
        ['isPdfMode' => true],
        'Moemen-Team-Cloud-Email-Handover-EN.pdf',
        $asDownload
    );
})->name('documents.handover.en');

// Official Single-Page Technical Handover Contract & Certificate
Route::get('/documents/technical-handover', function (Request $request) {
    $asDownload = $request->query('download') === '1';

    return DocumentPdfService::render(
        'documents.technical-handover',
        ['isPdfMode' => true],
        'Moemen-Team-Technical-Handover-Certificate.pdf',
        $asDownload
    );
})->name('documents.technical-handover');

// Public Direct Employee Account Card PDF (No dashboard login required!)
Route::get('/cards/{domain}/{user}/pdf', [EmailAccountController::class, 'publicCardPdf'])->name('emails.public-card');

require __DIR__.'/auth.php';
