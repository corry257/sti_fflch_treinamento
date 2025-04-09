<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;

// CREATE
Route::get('/livros/create', [LivroController::class, 'create']);
Route::post('/livros', [LivroController::class, 'store']);

// READ
Route::get('/livros', [LivroController::class, 'index']);
Route::get('/livros/{livro}', [LivroController::class, 'show']);

// UPDATE
Route::get('/livros/{livro}/edit', [LivroController::class, 'edit']);
Route::put('/livros/{livro}', [LivroController::class, 'update']);

// DELETE
Route::delete('/livros/{livro}', [LivroController::class,'destroy']);