<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [AuthenticatedSessionController::class, 'create']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('export')->group(function () {
        Route::get('/form/zoom-meetings', [ExportController::class, 'index'])->name('export.form');
        Route::post('/zoom-meetings', [ExportController::class, 'export'])->name('export.csv');
        Route::get('/download/{filename}', [ExportController::class, 'downloadFile'])->name('export.download');
    });

});

require __DIR__.'/auth.php';
