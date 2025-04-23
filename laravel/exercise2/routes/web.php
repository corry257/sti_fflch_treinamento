<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LivroController;

Route::get('/livros', [LivroController::class, 'index']);

Route::get('/livros/stats', [LivroController::class, 'stats']);
Route::get('/livros/stats/ano', [LivroController::class, 'statsAno']);
Route::get('/livros/stats/autor', [LivroController::class, 'statsAutor']);
Route::get('/livros/stats/idioma', [LivroController::class, 'statsIdioma']);

Route::get('/livros/importcsv', [LivroController::class, 'importCsv']);

Route::get('/livros/create', [LivroController::class, 'create']);
Route::post('/livros', [LivroController::class, 'store']);
Route::get('/livros/{livro}', [LivroController::class, 'show']);
Route::get('/livros/{livro}/edit', [LivroController::class, 'edit']);
Route::put('/livros/{livro}', [LivroController::class, 'update']);
Route::delete('/livros/{livro}', [LivroController::class, 'destroy']);

