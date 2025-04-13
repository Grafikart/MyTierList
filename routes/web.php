<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\MoviesController::class, 'index'])->name('home');
Route::get('/export', [\App\Http\Controllers\MoviesController::class, 'export'])->name('export');
Route::get('/sync', [\App\Http\Controllers\MoviesController::class, 'sync'])->name('sync');
