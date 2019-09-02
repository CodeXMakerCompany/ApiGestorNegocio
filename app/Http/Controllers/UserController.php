<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function pruebas(Request $request) {
    	return "Accion de pruebas de user controlador";
    }

    public function register(Request $request) {
    	
    	//Recoger los datos del usuario por post
    	$json = $request->input("json", null);

    	$params = json_decode($json);//Objeto
    	$params_array = json_decode($json, true); //array

    	if (!empty($params) && !empty($params_array)) {
    		//Limpiar datos
	    	$params_array = array_map('trim', $params_array);

	    	//Validar datos
	    	
	    	$validate = \Validator::make($params_array, [
	    		'nombre'    => 'required|alpha',
	    		'apellidos' => 'required|alpha',
	    		'telefono'  => 'required',
	    		'correo'    => 'required|email|unique:users',//Comprobar si el usuario existe ya users es la tabla en la bd
	    		'password'  => 'required'
	    	]);

	    	if ($validate->fails()) {
	    		//La validacion ha fallado
	    		$data = array(
	    		'status' => 'error',
	    		'code' => 404,
	    		'message' => 'El usuario no se ha creado',
	    		'errors' => $validate->errors()
	    		);

	    	}else{

	    		//validacion
	    		//Cifrar la contraseña
    			$pwd = hash('sha256', $params->password);  			
    	
    			//Crear el usuario

    			$user = new User();
    			$user->nombre    = $params_array['nombre'];
    			$user->apellidos = $params_array['apellidos'];
    			$user->telefono = $params_array['telefono'];
    			$user->correo    = $params_array['correo'];
    			$user->password  = $pwd;
    			$user->rol       = $params_array['rol'];

    			//Guardar el usuario
    			$user->save();

	    		$data = array(
	    		'status'  => 'success',
	    		'code'    => 200,
	    		'message' => 'El usuario se ha creado correctamente',
	    		'user'    => $user
	    		);

	    	}

    	}else{

    			$data = array(
	    		'status' => 'error',
	    		'code' => 404,
	    		'message' => 'Los datos enviados no son correctos'
	    		);
    	}
    	

    	
    	return response()->json($data, $data['code']);
    }

    public function login(Request $request) {

    	$jwtAuth = new \JwtAuth();

        //<Login usuario>
        
        //Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        //Validar datos
        $validate = \Validator::make($params_array, [
                'correo'    => 'required|email',//Comprobar si el usuario existe ya users es la tabla en la bd
                'password'  => 'required'
            ]);

        if ($validate->fails()) {
            //Validacion ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El suaurio no se ha podido validar'
                );
        }else{

            //Cifrar contraseña
            $pwd = hash('sha256', $params->password);

            //Devolver token o datos
            $signup = $jwtAuth->signup($params->correo, $pwd);

                if (!empty($params->gettoken)) {
                    $signup = $jwtAuth->signup($params->email, $pwd, true);
                }
        }
        
        

    	return response()->json($signup,200);
    }

    public function update(Request $request) {

        $token = $request->header('Authorization');
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken) {
            echo "<h1>Login correcto</h1>";
        }else{
            echo "<h1>Login incorrecto</h1>";
        }
        
        die();
    }
}
