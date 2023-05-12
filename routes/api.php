<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{  CreditRequestController, 
                            AaMortizacioncontroller,
                            UserController,
                            MensajeController,
                            BancoDeDatosController, ComentarioController};

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
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-users',[UserController::class, 'index'])->name('get.users');
    Route::get('/get-users-clientes',[UserController::class, 'indexClientes'])->name('get.users.cliente');
    Route::post('/create-new-cliente',[UserController::class, 'create'])->name('create.new.cliente');
    Route::get('/view-cliente/{id}',[UserController::class, 'viewCliente'])->name('view.cliente');
    Route::post('/edit-cliente/{id}',[UserController::class, 'update'])->name('edit.cliente');
    Route::get('/get-credit-request',[CreditRequestController::class, 'index'])->name('get.credit.request');
    Route::post('/enviar-credito-a-estudio',[CreditRequestController::class, 'enviarCreditoAEstudio'])->name('enviar.credito.a.estuidio');
    Route::post('/rechazar-credito',[CreditRequestController::class, 'rechazarCredito'])->name('rechazar.credito');
    Route::get('/get-historial-user/{id}',[CreditRequestController::class, 'indexHistorialUser'])->name('get.historial.user');
    Route::get('/get-recent-credit-request/{id}',[CreditRequestController::class, 'indexRecentCreditRequestUser'])->name('get.recent.credit.request');
    Route::post('/add-recibo-de-pago-user',[AaMortizacioncontroller::class, 'store'])->name('add.recibo.de.pago.user');
    Route::post('/add-factura-de-pago-user',[AaMortizacioncontroller::class, 'storeFactura'])->name('add.factura.de.pago.user');
    Route::post('/recalcular-amortizaciones',[AaMortizacioncontroller::class, 'recalcularAmortizaciones'])->name('recalcular.amortizaciones');
    Route::post('/send-new-mensaje',[MensajeController::class, 'store'])->name('send.new.mensaje');
    Route::get('/get-mensajes',[MensajeController::class, 'index'])->name('get.mensajes');
    Route::post('/responder-mensajes',[MensajeController::class, 'ResponderMensaje'])->name('responder.mensaje');
    Route::post('/add-upload-file-json',[BancoDeDatosController::class, 'saveUploadJson'])->name('add.upload.file.json');
    Route::get('/get-banco-de-datos',[BancoDeDatosController::class, 'index'])->name('get.banco.de.datos');
    Route::put('/edit-banco-de-datos',[BancoDeDatosController::class, 'edit'])->name('edit.user.document');
    Route::post('/asociardar-datos-a-usuario',[BancoDeDatosController::class, 'asociarDocumento'])->name('asociar.datos.a.usuario');
    Route::post('/add-comentario',[ComentarioController::class, 'store'])->name('add.comentario');
// });


