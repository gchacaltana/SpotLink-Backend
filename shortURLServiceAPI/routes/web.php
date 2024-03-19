<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json([
        'api' => [
            'name' => 'SpotLink API',
            'version' => '1.0.0',
            'description' => 'API handles the creation and redirection of short URLs'
        ]
    ]);
});
