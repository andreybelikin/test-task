<?php

use App\Http\Controllers\ForecastController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureApiKeySet;
use App\Http\Middleware\ValidateRequestParams;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/forecast', [ForecastController::class, 'show'])->middleware([EnsureApiKeySet::class, ValidateRequestParams::class]);
