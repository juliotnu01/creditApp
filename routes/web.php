<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CreditRequestController;
use Symfony\Component\HttpFoundation\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Route::post('/login-user', Fortify::authenticateUsing([new UserController, '__invoke']) )->name('user.login');

Route::get('/credit-request', function () {
    return Inertia::render('creditRequest',[
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('credit.request');




Route::middleware([ 'auth:sanctum', config('jetstream.auth_session'),'verified'])->group(function () {
    
    Route::get('/dashboard', function (Request $request) {
        if($request->user()->raw_rol == 'admin'){
            $isCliente = false;
            return Inertia::location('solicitudes-de-creditos', ["isCliente" => $isCliente]);
        }elseif($request->user()->raw_rol == 'cliente'){
            $isCliente = true;
            return Inertia::render('Dashboard', ["isCliente" => $isCliente, "user_id" => $request->user()->id]);
        }
    })->name('dashboard');
    
    Route::get('/solicitudes-de-creditos', function () {
        return Inertia::render('HistorialCreditRequest/index');
    })->name('credits.requests');

    Route::get('/usuarios', function () {
        return Inertia::render('Usuarios/index');
    })->name('usuarios');

    Route::get('/banco-de-datos', function () {
        return Inertia::render('BancoDeDatos/index');
    })->name('banco.de.datos');

    Route::prefix('crear-creditos')->name('crear.creditos.')->group(function () {

        Route::get('/', function () {
            return Inertia::render('CrearCreditos/index');
        })->name('index');
    
        Route::get('/analizis/{user?}/{uui?}', function ($user = '', $uui = '') {
            return Inertia::render('CrearCreditos/AnalizisCredito', ["user" => $user, "uui" => $uui]);
        })->name('analizis');
    });
});
