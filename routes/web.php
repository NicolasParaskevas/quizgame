<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\QuizController::class, 'index']);
Route::post('/start-quiz',[App\Http\Controllers\QuizController::class, 'start'])->name('start-quiz');
Route::get('/quiz',[App\Http\Controllers\QuizController::class, 'quiz'])->name('quiz');
Route::get('/results',[App\Http\Controllers\QuizController::class, 'results'])->name('results');