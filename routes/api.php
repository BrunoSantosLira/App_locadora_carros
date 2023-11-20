<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CarroController;
use App\Http\Controllers\LocacaoController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\MarcaController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('v1')->middleware('jwt.auth')->group(function () {
    Route::post('me',[\App\Http\Controllers\AuthController::class, 'me']);
    Route::post('logout',[\App\Http\Controllers\AuthController::class, 'logout']);
    Route::apiresource('cliente', ClienteController::class);
    Route::apiresource('marca', MarcaController::class);
    Route::apiresource('modelo', ModeloController::class);
    Route::apiresource('carro', CarroController::class);
    Route::apiresource('locacao', LocacaoController::class);
});



Route::post('login',[\App\Http\Controllers\AuthController::class, 'login']);
Route::post('refresh',[\App\Http\Controllers\AuthController::class, 'refresh']);


