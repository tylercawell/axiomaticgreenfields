<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'permission:view commission notes|manage commission notes'])->group(function () {
    Route::get('commission-notes', [App\Http\Controllers\CommissionNoteController::class, 'index'])->name('commissionNotes');
    Route::post('commission-notes', [App\Http\Controllers\CommissionNoteController::class, 'store'])->name('commissionNotes.store');
    Route::put('commission-notes/{commissionNote}', [App\Http\Controllers\CommissionNoteController::class, 'update'])->name('commissionNotes.update');
});

Route::middleware(['auth', 'permission:manage companies|view companies'])->group(function () {
    Route::get('companies', [App\Http\Controllers\CompanyController::class, 'index'])->name('companies');
});


require __DIR__.'/settings.php';
