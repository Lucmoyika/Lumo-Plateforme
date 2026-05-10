<?php

use App\Modules\Education\Ecoles\SubModules\Maternelle\Controllers\MaternelleClassController;
use App\Modules\Education\Ecoles\SubModules\Maternelle\Controllers\MaternelleTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('maternelle/schools/{school}')->group(function () {
	Route::get('/classes', [MaternelleClassController::class, 'index']);
	Route::post('/classes', [MaternelleClassController::class, 'store']);
	Route::delete('/classes/{class}', [MaternelleClassController::class, 'destroy']);

	Route::get('/teachers', [MaternelleTeacherController::class, 'index']);
	Route::post('/teachers', [MaternelleTeacherController::class, 'store']);
	Route::delete('/teachers/{teacher}', [MaternelleTeacherController::class, 'destroy']);
});
