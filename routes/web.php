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
Route::get('/categoria/{id}','Categoria\CategoriaController@show');
Route::get('/selectCategoria','Categoria\CategoriaController@selectCategoria');
Route::post('/categoria','Categoria\CategoriaController@store');
Route::put('/categoria/actualizar','Categoria\CategoriaController@update');
Route::put('/categoria/desactivar','Categoria\CategoriaController@desactivar');
Route::put('/categoria/activar','Categoria\CategoriaController@activar');


Route::get('/articulo','Articulo\ArticuloController@index');
Route::get('/articulo/{id}','Articulo\ArticuloController@show');
Route::post('/articulo','Articulo\ArticuloController@store');
Route::put('/articulo/actualizar','Articulo\ArticuloController@update');
Route::put('/articulo/desactivar','Articulo\ArticuloController@desactivar');
Route::put('/articulo/activar','Articulo\ArticuloController@activar');
