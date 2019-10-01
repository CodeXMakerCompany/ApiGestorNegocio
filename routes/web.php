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

//Cargando clases
use App\Http\Middleware\ApiAuthMiddleware;

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
	Route::put('/api/user/update', 'UserController@update');
	Route::post('/api/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);

	Route::get('/api/user/avatar/{filename}', 'UserController@getImage');
	Route::get('/api/user/detail/{id}', 'UserController@detail');

	//Rutas del controlador de habitaciones
	 /*Ruta de tipo resource !*/
	Route::resource('/api/habitacion', 'HabitacionController'); 

	//Rutas del controlador de productos
	 /*Ruta de tipo resource !*/
	Route::resource('/api/producto', 'ProductoController');
	Route::post('/api/producto/upload', 'ProductoController@upload'); 
	Route::get('/api/producto/image/{filename}', 'ProductoController@getImage');
	Route::get('/api/producto/category/{id}', 'ProductoController@getProductsByCategory');
	