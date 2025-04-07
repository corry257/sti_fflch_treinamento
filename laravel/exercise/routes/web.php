<?php

use App\Http\Controllers\ExerciseController;

Route::get('/exercises/importcsv', [ExerciseController::class, 'importCsv']);
Route::get('/exercises/stats', [ExerciseController::class, 'stats']);
