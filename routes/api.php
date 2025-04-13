<?php

Route::post('/movies/{movie}/tier/{tier}', [\App\Http\Controllers\MoviesController::class, 'tier'])->name('tier');
Route::post('/movies/move', [\App\Http\Controllers\MoviesController::class, 'move'])->name('move');
