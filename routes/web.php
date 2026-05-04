<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ResultController;

Route::redirect('/', '/quizzes');

// Quiz Routes
Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
Route::patch('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

// Question Routes
Route::get('/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::get('/quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
Route::patch('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
Route::delete('/quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

// Attempt Routes
Route::get('/quizzes/{quiz}/start', [AttemptController::class, 'start'])->name('attempts.start');
Route::post('/quizzes/{quiz}/start', [AttemptController::class, 'create'])->name('attempts.create');
Route::get('/quizzes/{quiz}/attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
Route::get('/quizzes/{quiz}/attempts', [AttemptController::class, 'list'])->name('attempts.list');

// Answer Routes
Route::post('/quizzes/{quiz}/attempts/{attempt}/submit', [AnswerController::class, 'submit'])->name('answers.submit');

// Result Routes
Route::get('/quizzes/{quiz}/attempts/{attempt}/results', [ResultController::class, 'show'])->name('results.show');
