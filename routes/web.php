<?php

use Illuminate\Support\Facades\Route;

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
    return view('contenido/contenido');
});
Route::get('/categoria','Categoria\CategoriaController@index');
Route::post('/categoria','Categoria\CategoriaController@store');
Route::put('/categoria','Categoria\CategoriaController@update');
Route::put('/categoria/desactivar','Categoria\CategoriaController@desactivar');
Route::put('/categoria/activar','Categoria\CategoriaController@activar');
