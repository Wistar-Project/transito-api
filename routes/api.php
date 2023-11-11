<?php

use App\Http\Controllers\EntregasController;
use App\Http\Controllers\LoteController;
use App\Http\Controllers\PaqueteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function(){
        Route::get('/paquete/{d}', [PaqueteController::class, "ObtenerEstado"]) -> middleware('admin-o-gerente');
        Route::get('/lote/{d}', [LoteController::class, "ObtenerEstado"]) -> middleware('admin-o-gerente');
        Route::get('/entregas', [EntregasController::class, "Mostrar"]) -> middleware('chofer-o-mayor');
        Route::get('/entregas/{d}', [EntregasController::class, "MostrarDescarga"]) -> middleware('chofer-o-mayor');
        Route::delete('/entregas/{d}', [EntregasController::class, "MarcarEntregada"]) -> middleware('chofer-o-mayor');
    });
});