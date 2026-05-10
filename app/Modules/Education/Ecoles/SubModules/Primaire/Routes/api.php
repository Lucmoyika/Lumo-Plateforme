<?php

use App\Modules\Education\Ecoles\SubModules\Primaire\Controllers\PrimaireClassController;
use App\Modules\Education\Ecoles\SubModules\Primaire\Controllers\PrimaireTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('primaire/schools/{school}')->group(function () {
	Route::get('/classes', [PrimaireClassController::class, 'index']);
	Route::post('/classes', [PrimaireClassController::class, 'store']);
	Route::delete('/classes/{class}', [PrimaireClassController::class, 'destroy']);

	Route::get('/teachers', [PrimaireTeacherController::class, 'index']);
	Route::post('/teachers', [PrimaireTeacherController::class, 'store']);
});
