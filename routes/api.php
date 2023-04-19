<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreditRequestController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-credit-request',[CreditRequestController::class, 'index'])->name('get.credit.request');
    Route::post('/enviar-credito-a-estudio',[CreditRequestController::class, 'enviarCreditoAEstudio'])->name('enviar.credito.a.estuidio');
    Route::post('/rechazar-credito',[CreditRequestController::class, 'rechazarCredito'])->name('rechazar.credito');
});


