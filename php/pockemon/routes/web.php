<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PockemonController;

Route::get('/', function () {
    return redirect()->route('pockemons.index');
});

Route::get('/pockemons', [PockemonController::class, 'index'])->name('pockemons.index');
Route::get('/pockemons/{id}/{slug?}', [PockemonController::class, 'show'])->name('pockemons.show');