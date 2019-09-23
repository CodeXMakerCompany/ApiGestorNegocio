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

//rutas de prueba
Route::get('/', function () {
    return view('welcome');
});

Route::get('test', 'pruebasController@testOrm');

	//Rutas del servicio pruebas
	Route::get('/usuario/pruebas', 'UserController@pruebas');

	Route::get('/categoria/pruebas', 'CategoriaController@pruebas');

	Route::get('/cliente/pruebas', 'ClienteController@pruebas');

	Route::get('/habitacion/pruebas', 'HabitacionController@pruebas');

	Route::get('/producto/pruebas', 'ProductoController@pruebas');

	Route::get('/venta/pruebas', 'VentaController@pruebas');

	//Rutas del controlador del usuario
	Route::post('/api/register', 'UserController@register');
	Route::post('/api/login', 'UserController@login');
	//Metodo http para actualizar registros
	Route::put('/api/update', 'UserController@update');

