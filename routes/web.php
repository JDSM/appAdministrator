<?php

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
Route::group(['middleware' => 'guest'], function () {
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});
Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout','Auth\LoginController@logout')->name('logout');
    Route::get('/dashboard', 'DashboardController');
    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');
    Route::group(['middleware' => 'Almacenista'], function () {
        //Categoria
        Route::get('/categoria','CategoriaController@index');
        Route::post('/categoria/registrar','CategoriaController@store');
        Route::put('/categoria/actualizar','CategoriaController@update');
        Route::put('/categoria/desactivar','CategoriaController@desactivar');
        Route::put('/categoria/activar','CategoriaController@activar');
        Route::get('/categoria/selectCategoria','CategoriaController@selectCategoria');    
        //Articulo
        Route::get('/articulo','ArticuloController@index');
        Route::post('/articulo/registrar','ArticuloController@store');
        Route::put('/articulo/actualizar','ArticuloController@update');
        Route::put('/articulo/desactivar','ArticuloController@desactivar');
        Route::put('/articulo/activar','ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo','ArticuloController@listarArticulo');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');
        //Proveedor
        Route::get('/proveedor','ProveedorController@index');
        Route::post('/proveedor/registrar','ProveedorController@store');
        Route::put('/proveedor/actualizar','ProveedorController@desactivar');
        Route::get('/proveedor/selectProveedor','ProveedorController@selectProveedor');
        //ingreso
        Route::get('/ingreso','IngresoController@index');
        Route::post('/ingreso/registrar','IngresoController@store');
        Route::put('/ingreso/desactivar','IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera','IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalle','IngresoController@obtenerDetalle');
    });
    
    Route::group(['middleware' => 'Vendedor'], function () {
        //Cliente
        Route::get('/cliente','ClienteController@index');
        Route::post('/cliente/registrar','ClienteController@store');
        Route::put('/cliente/actualizar','ClienteController@update');
        Route::get('/cliente/selectCliente','ClienteController@selectCliente');
        //Articulo
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta','ArticuloController@listarArticuloVenta');
        //Venta
        Route::get('/venta','VentaController@index');
        Route::post('/venta/registrar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerCabecera','VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles','VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
    });
    
    Route::group(['middleware' => ['Administrador']], function () {
        //Receta
        Route::get('/receta','RecetaController@index');
        Route::get('/receta/edit','RecetaController@edit');
        Route::get('/receta/obtenerDetalles','RecetaController@obtenerDetalles');
        Route::post('/receta/registrar','RecetaController@store');
        Route::put('/receta/desactivar','RecetaController@desactivar');
        //Categoria
        Route::get('/categoria','CategoriaController@index');
        Route::post('/categoria/registrar','CategoriaController@store');
        Route::put('/categoria/actualizar','CategoriaController@update');
        Route::put('/categoria/desactivar','CategoriaController@desactivar');
        Route::put('/categoria/activar','CategoriaController@activar');
        Route::get('/categoria/selectCategoria','CategoriaController@selectCategoria');    
        //Articulo
        Route::get('/articulo','ArticuloController@index');
        Route::post('/articulo/registrar','ArticuloController@store');
        Route::put('/articulo/actualizar','ArticuloController@update');
        Route::put('/articulo/desactivar','ArticuloController@desactivar');
        Route::put('/articulo/activar','ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo','ArticuloController@listarArticulo');
        Route::get('/articulo/listarArticuloVenta','ArticuloController@listarArticuloVenta');
        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarPdf', 'ArticuloController@listarPdf')->name('articulos_pdf');
        //Proveedor
        Route::get('/proveedor','ProveedorController@index');
        Route::post('/proveedor/registrar','ProveedorController@store');
        Route::put('/proveedor/actualizar','ProveedorController@update');
        Route::get('/proveedor/selectProveedor','ProveedorController@selectProveedor');
        //Cliente
        Route::get('/cliente','ClienteController@index');
        Route::post('/cliente/registrar','ClienteController@store');
        Route::put('/cliente/actualizar','ClienteController@update');
        Route::get('/cliente/selectCliente','ClienteController@selectCliente');
        //Rol
        Route::get('/rol','RolController@index');
        Route::get('/rol/selectRol','RolController@selectRol');
        //Usuario
        Route::get('/user','UserController@index');
        Route::post('/user/registrar','UserController@store');
        Route::put('/user/actualizar','UserController@update');
        Route::put('/user/desactivar','UserController@desactivar');
        Route::put('/user/activar','UserController@activar');
        //ingreso
        Route::get('/ingreso','IngresoController@index');
        Route::get('/ingreso/obtenerCabecera','IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles','IngresoController@obtenerDetalles');
        Route::post('/ingreso/registrar','IngresoController@store');
        Route::put('/ingreso/desactivar','IngresoController@desactivar');
        //Venta
        Route::get('/venta','VentaController@index');
        Route::post('/venta/registrar','VentaController@store');
        Route::put('/venta/desactivar','VentaController@desactivar');
        Route::get('/venta/obtenerCabecera','VentaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles','VentaController@obtenerDetalles');
        Route::get('/venta/pdf/{id}','VentaController@pdf')->name('venta_pdf');
    });
});

//Route::get('/home', 'HomeController@index')->name('home');
