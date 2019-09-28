<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Habitacion;

class HabitacionController extends Controller
{	

   public function __construct(){
   		$this->middleware('api.auth', ['except' => ['index', 'show']]);
   }

   public function index(){

   	$habitaciones = Habitacion::all();

   	return response()->json([
   		'code' => 200,
   		'status' => 'success',
   		'habitaciones' => $habitaciones
   	]);

   }

   public function show($id) {
   		$habitacion = Habitacion::find($id);

   		if (is_object($habitacion)) {
   			$data = [
   				'code' => 200,
   				'status' => 'success',
   				'habitacion' => $habitacion
   			];
   		}else{
   			$data = [
   				'code' => 404,
   				'status' => 'error',
   				'habitacion' => 'La habitacion no existe'
   			];
   		}

   		return response()->json($data, $data['code']);
   }

   public function store(Request $request){
   		//Recoger datos por post
   		$json = $request->input('json', null);
   		$params_array = json_decode($json, true);

   		if (!empty($params_array)) {
   		//Validar datos
   		$validate = \Validator::make($params_array, [
   			'numero' => 'required',
   			'tipo' => 'required',
   			'estado' => 'required',
   			'telefono' => 'required',
   			'contenido' => 'required',
   			'precio' => 'required'
   		]);

   		//Guardar habitacion
   		if ($validate->fails()) {
   			$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'No se ha guardado la habitacion.'
   			];
   		}else{
   			$habitacion = new Habitacion();
   			$habitacion->numero = $params_array['numero'];
   			$habitacion->tipo = $params_array['tipo'];
   			$habitacion->estado = $params_array['estado'];
   			$habitacion->telefono = $params_array['telefono'];
   			$habitacion->contenido = $params_array['contenido'];
   			$habitacion->precio = $params_array['precio'];

   			$habitacion->save();

   			$data = [
   				'code' => 200,
   				'status' => 'success',
   				'habitacion' => $habitacion
   			];

   		}
   	}else{
   		$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'No se ha enviado ninguna habitacion.'
   			];
   	}	
   		//devolver resultado
   		return response()->json($data, $data['code']);
   }

   public function update($id, Request $request){
   		//Recoger datos por post
   		$json = $request->input('json', null);
   		$params_array = json_decode($json, true);

   		if (!empty($params_array)) {
   		
   		//Validar datos
   		$validate = \Validator::make($params_array, [
   			'numero' => 'required',
   			'tipo' => 'required',
   			'estado' => 'required',
   			'telefono' => 'required',
   			'contenido' => 'required',
   			'precio' => 'required'
   		]);
   		
   		//Quitar lo no actualizable
   		unset($params_array['id']);
   		unset($params_array['created_at']);
   		
   		//Actualizar el registro
   		$habitacion = Habitacion::where('id', $id)->update($params_array);

   		$data = [
   				'code' => 200,
   				'status' => 'success',
   				'habitacion' => $params_array
   			];
   		
   		}else{
   			$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'No se actualizado ningun registro de habitacion.'
   			];
   		}

   		//Devolver respuesta
   		//
   		return response()->json($data, $data['code']);
   }

}
