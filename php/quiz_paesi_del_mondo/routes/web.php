<?php

use App\Http\Controllers\AllenamentoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/allenamento', [AllenamentoController::class, 'show'])->name('allenamento');

Route::post('/quiz/avvia', [QuizController::class, 'avvia'])->name('quiz.avvia');
Route::get('/quiz', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz/rispondi', [QuizController::class, 'rispondi'])->name('quiz.rispondi');
Route::get('/risultato', [QuizController::class, 'risultato'])->name('quiz.risultato');
