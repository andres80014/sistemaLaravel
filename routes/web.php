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

Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login','Auth\LoginController@login')->name('login');
});


//Route::get('/','Auth\LoginController@showLoginForm');
//Route::post('/login','Auth\LoginController@login')->name('login');

Route::group(['middleware' => ['auth']], function () {

    Route::post('/logout','Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::get('/proveedor/selectProveedor','Proveedor\ProveedorController@selectProveedor');
    Route::get('/articulo/buscarArticulo','Articulo\ArticuloController@buscarArticulo');
    Route::get('/articulo/buscarArticuloVenta','Articulo\ArticuloController@buscarArticuloVenta');

    Route::get('/articulo/listarArticulos','Articulo\ArticuloController@listarArticulos');
    Route::get('/articulo/listarArticulosStock','Articulo\ArticuloController@listarArticulosStock');


    Route::get('/ingreso/obtenerDetalles','Ingreso\IngresoController@obtenerDetalles');
    Route::get('/ingreso/obtenerCabecera','Ingreso\IngresoController@obtenerCabecera');
    Route::get('/cliente/selectCliente','Cliente\ClienteController@selectCliente');

    Route::get('/venta','Venta\VentaController@index');
    Route::post('/venta','Venta\VentaController@store');
    Route::get('/venta/obtenerDetalles','Venta\VentaController@obtenerDetalles');
    Route::get('/venta/obtenerCabecera','Venta\VentaController@obtenerCabecera');
    Route::put('/venta/desactivar','Venta\VentaController@desactivar');

    Route::group(['middleware' => ['almacenero']], function () {
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

        Route::get('/proveedor','Proveedor\ProveedorController@index');
        Route::get('/proveedor/{id}','Proveedor\ProveedorController@show');
        Route::post('/proveedor','Proveedor\ProveedorController@store');
        Route::put('/proveedor/actualizar','Proveedor\ProveedorController@update');

    });


    Route::group(['middleware' => ['vendedor']], function () {
        Route::get('/cliente','Cliente\ClienteController@index');
        Route::get('/cliente/{id}','Cliente\ClienteController@show');
        Route::post('/cliente','Cliente\ClienteController@store');
        Route::put('/cliente/actualizar','Cliente\ClienteController@update');
    });

    Route::group(['middleware' => ['administrador']], function () {

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

        Route::get('/proveedor','Proveedor\ProveedorController@index');
        Route::get('/proveedor/{id}','Proveedor\ProveedorController@show');
        Route::post('/proveedor','Proveedor\ProveedorController@store');
        Route::put('/proveedor/actualizar','Proveedor\ProveedorController@update');
        Route::get('/proveedor/select','Proveedor\ProveedorController@index');

        Route::get('/cliente','Cliente\ClienteController@index');
        Route::get('/cliente/{id}','Cliente\ClienteController@show');
        Route::post('/cliente','Cliente\ClienteController@store');
        Route::put('/cliente/actualizar','Cliente\ClienteController@update');

        Route::get('/rol','Rol\RolController@index');
        Route::get('/selectRol','Rol\RolController@selectRol');

        Route::get('/user','User\UserController@index');
        Route::get('/user/{id}','User\UserController@show');
        Route::post('/user','User\UserController@store');
        Route::put('/user/actualizar','User\UserController@update');
        Route::put('/user/desactivar','User\UserController@desactivar');
        Route::put('/user/activar','User\UserController@activar');

        Route::get('/ingreso','Ingreso\IngresoController@index');
        Route::post('/ingreso','Ingreso\IngresoController@store');
        Route::put('/ingreso/desactivar','Ingreso\IngresoController@desactivar');
    });
});

/*
Route::get('/main', function () {
    return view('contenido/contenido');
})->name('main');

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


Route::get('/cliente','Cliente\ClienteController@index');
Route::get('/cliente/{id}','Cliente\ClienteController@show');
Route::post('/cliente','Cliente\ClienteController@store');
Route::put('/cliente/actualizar','Cliente\ClienteController@update');


Route::get('/proveedor','Proveedor\ProveedorController@index');
Route::get('/proveedor/{id}','Proveedor\ProveedorController@show');
Route::post('/proveedor','Proveedor\ProveedorController@store');
Route::put('/proveedor/actualizar','Proveedor\ProveedorController@update');

Route::get('/rol','Rol\RolController@index');
Route::get('/selectRol','Rol\RolController@selectRol');

Route::get('/user','User\UserController@index');
Route::get('/user/{id}','User\UserController@show');
Route::post('/user','User\UserController@store');
Route::put('/user/actualizar','User\UserController@update');
Route::put('/user/desactivar','User\UserController@desactivar');
Route::put('/user/activar','User\UserController@activar');

Route::get('/','Auth\LoginController@showLoginForm');
Route::post('/login','Auth\LoginController@login')->name('login');

Route::get('/home', 'HomeController@index')->name('home');
*/

