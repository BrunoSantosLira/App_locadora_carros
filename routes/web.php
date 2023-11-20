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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/marcas', function(){
    return view('app.marcas');
})->name('marcas');

Route::get('/clientes', function(){
    return view('app.clientes');
})->name('clientes');

Route::get('/modelos', function(){
    return view('app.modelos');
})->name('modelos');


Route::get('/carros', function(){
    return view('app.carros');
})->name('carros');

Route::get('/locacoes', function(){
    return view('app.locacoes');
})->name('locacoes');

// usage inside a laravel route
Route::get('/teste', function() {
    $img = Image::make('C:\Users\bruno\Desktop\code_laravel\app_locadora_carros\public\storage\imagens\negocios3.png')->resize(300, 200);
    $img = Image::make('C:\Users\bruno\Desktop\code_laravel\app_locadora_carros\public\storage\imagens\negocios3.png')->encode('webp');
    $img->save('C:\Users\bruno\Desktop\code_laravel\app_locadora_carros\public\storage\imagens\negocios3.gif');
});