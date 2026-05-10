<?php

use App\Modules\Education\Ecoles\SubModules\Humanites\Controllers\HumanitesClassController;
use App\Modules\Education\Ecoles\SubModules\Humanites\Controllers\HumanitesTeacherController;
use Illuminate\Support\Facades\Route;

Route::prefix('humanites/schools/{school}')->group(function () {
	Route::get('/classes', [HumanitesClassController::class, 'index']);
	Route::post('/classes', [HumanitesClassController::class, 'store']);

	Route::get('/teachers', [HumanitesTeacherController::class, 'index']);
});
