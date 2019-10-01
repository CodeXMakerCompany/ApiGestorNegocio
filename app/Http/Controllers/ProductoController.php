<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Producto;
use App\Helpers\JwtAuth;

class ProductoController extends Controller
{	
	public function __construct(){
   		$this->middleware('api.auth', ['except' => 
   			['index', 
   			 'show', 
   			 'getImage',
   			 'getProductsByCategory'
   			]]);
   }

    public function index() {
    	$productos = Producto::all()->load('categoria');

    	return response()->json([
    		'code' => 200,
    		'status' => 'success',
    		'productos' => $productos
    	], 200);
    }

    public function show($id){
    	$producto = Producto::find($id)->load('categoria');

    	if (is_object($producto)) {
    		$data = [
    			'code' => 200,
    			'status' => 'success',
    			'productos' => $producto
    		];
    	}else{
    		$data = [
    			'code' => 404,
    			'status' => 'error',
    			'message' => 'El producto no existe'
    		];
    	}

    	return response()->json($data, $data['code']);
    }

    public function store(Request $request){
    	//Recoger datos por post
    	$json = $request->input('json', null);
    	$params = json_decode($json);
    	$params_array = json_decode($json, true);

    	if (!empty($params_array)) {
    		//Conseguir user identificado
    		$JwtAuth = new JwtAuth();
    		$token = $request->header('Authorization', null);
    		$user = $JwtAuth->checkToken($token, true);

    		//Validar los datos
    		$validate = \Validator::make($params_array, [
    			'nombre' => 'required',
    			'descripcion' => 'required',
    			'tipo' => 'required',
    			'multimedia' => 'required',
    			'precio' => 'required',
    			'cantidad' => 'required'
    		]);

    		if ($validate->fails()) {
    			$data = [
    				'code' => 404,
    				'status' => 'error',
    				'message' => 'Faltan datos'
    			];
    		}else{
    			//Guardar el articulo
    			$producto = new Producto();
    			$producto->id_categoria = $params->id_categoria;
    			$producto->nombre = $params->nombre;
    			$producto->descripcion = $params->descripcion;
    			$producto->tipo = $params->tipo;
    			$producto->multimedia = $params->multimedia;
    			$producto->precio = $params->precio;
    			$producto->cantidad = $params->cantidad;

    			$producto->save();

    			$data = [
    			'code' => 200,
    			'status' => 'success',
    			'producto' => $producto
    			];
    		}
    	
    	}else{
    		$data = [
    				'code' => 404,
    				'status' => 'error',
    				'message' => 'Envia los datos correctamente'
    			];
    	}
    	
    	
    	//Devolver la respuesta
    	return response()->json($data, $data['code']);
    }

    public function update($id, Request $request){
   		//Recoger datos por post
   		$json = $request->input('json', null);
   		$params_array = json_decode($json, true);

   	
   			$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'datos enviado incorrectamente'
   			];


   		if (!empty($params_array)) {
   		//Validar datos
   		$validate = \Validator::make($params_array, [
   			'id_categoria' => 'required',
   				'nombre' => 'required',
    			'descripcion' => 'required',
    			'tipo' => 'required',
    			'multimedia' => 'required',
    			'precio' => 'required',
    			'cantidad' => 'required' 
   		]);


   			if ($validate->fails()) {
   				$data['errors'] = $validate->errors();
   				return response()->json($data, $data['code']);
   			}
   		//Eliminar lo que no se actualizara
   		unset($params_array['id']);
   		unset($params_array['created_at']);
   		unset($params_array['updated_at']);

   		//Actualizar el registro
   		$producto = Producto::where('id', $id)->update($params_array);

   		//Devolver respuesta
   		$data = [
   				'code' => 200,
   				'status' => 'success',
   				'producto_existencia' => $producto,
   				'changes' => $params_array
   			];
   			# code...
   		}

   		return response()->json($data, $data['code']);	
   }

   public function destroy($id, Request $request){
   	//Conseguir el registro
   	$producto = Producto::find($id);
   	
   	if (!empty($producto)) {
   		//Borrarlo
   		$producto->delete();
   			//Devolver algo
	   	$data = [
	   		'code' => 200,
	   				'status' => 'success',
	   				'producto' => $producto
	   	];

   	}else{
   		$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'No hay producto que borrar'
   			];
   	} 
   	return response()->json($data, $data['code']);

   }

   public function upload(Request $request){
   	//Recoger imagen de la peticion
   	$image = $request->file('file0');

   	//Validar imagen
   	$validate = \Validator::make($request->all(), [
   			'file0' => 'required|image|mimes:png,jpg,jpeg,gif'
   		]);
   	
   	//Guardar img en disco
   	if (!$image || $validate->fails()) {
   		$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'Error al subir la imagen'
   			];
   	}else{
   		$image_name = $image->getClientOriginalName();
   		\Storage::disk('images')->put($image_name, \File::get($image));

   			$data = [
	   		'code' => 200,
	   				'status' => 'success',
	   				'image' => $image_name
	   	];
   	}

   	//Devolver datos
   	
   	return response()->json($data, $data['code']);

   }

   public function getImage($filename){
   		//Comprobar si existe el fichero
   		$isset = \Storage::disk('images')->exists($filename);

   		if ($isset) {
   		//Conseguir la imagen
   		$file = \Storage::disk('images')->get($filename);

   		//Devolver la imagen
   		return new Response($file, 200);
   		
   		}else{
   			$data = [
   				'code' => 400,
   				'status' => 'error',
   				'message' => 'La imagen no se encuentra en la base de datos.'
   			];
   		}
   		////Mostrar error
   		return response()->json($data, $data['code']);
   	
   }

   public function getProductsByCategory($id){
   	$productos = Producto::where('id_categoria', $id)->get();

   	return response()->json([
   		'status' => 'success',
   		'productos' => $productos
   	],200);

   }

}
