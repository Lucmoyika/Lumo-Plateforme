<?php

use App\Modules\Education\Ecoles\SubModules\Secondaire\Controllers\SecondaireClassController;
use App\Modules\Education\Ecoles\SubModules\Secondaire\Controllers\SecondaireTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('secondaire/schools/{school}')->group(function () {
	Route::get('/classes', [SecondaireClassController::class, 'index']);
	Route::post('/classes', [SecondaireClassController::class, 'store']);

	Route::get('/teachers', [SecondaireTeacherController::class, 'index']);
});
