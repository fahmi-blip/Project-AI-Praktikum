<?php

use App\Http\Controllers\DiagnosisController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('diagnosis.create'));

// Form diagnosa
Route::get('/diagnosis',             [DiagnosisController::class, 'create'])->name('diagnosis.create');
Route::post('/diagnosis',            [DiagnosisController::class, 'store'])->name('diagnosis.store');
Route::get('/diagnosis/{diagnosis}', [DiagnosisController::class, 'show'])->name('diagnosis.show');
Route::delete('/diagnosis/{diagnosis}', [DiagnosisController::class, 'destroy'])->name('diagnosis.destroy');

Route::get('/diagnosis/{diagnosis}/download', [DiagnosisController::class, 'downloadPdf'])->name('diagnosis.download');