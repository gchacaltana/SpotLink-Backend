<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
});

Route::prefix('v1/links')->middleware('auth.bearer')->group(function () {
    // Ruta que requiere autenticación Bearer
    Route::post('/', [LinkController::class, 'create']);
});

Route::prefix('v1/links')->middleware('auth.bearer')->group(function () {
    // Ruta que requiere autenticación Bearer
    Route::get('/', [LinkController::class, 'getLinksByUser']);
});

Route::prefix('v1/links')->group(function () {
    // Ruta que no requiere autenticación Bearer
    Route::get('/{token}', [LinkController::class, 'getUrlByToken']);
});
