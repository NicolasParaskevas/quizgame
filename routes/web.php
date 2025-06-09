<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::post('/start-quiz',[App\Http\Controllers\HomeController::class, 'start'])->name('start-quiz');

Route::get('/quiz',[App\Http\Controllers\QuizController::class, 'index'])->name('quiz');
Route::post('/answer',[App\Http\Controllers\QuizController::class, 'answer'])->name('answer');
Route::get('/question/{index}',[App\Http\Controllers\QuizController::class, 'question'])->name('question');
Route::get('/results',[App\Http\Controllers\QuizController::class, 'results'])->name('results');