<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Habitacion;

class pruebasController extends Controller
{
    public function testOrm(){
    	
    	$habitaciones = Habitacion::all();
    	foreach ($habitaciones as $habitacion) {
    		echo "<h1>".$habitacion->numero."</h1>";
    		if ($habitacion->estado == "LIBRE") {
    			echo "Disponible registre a su cliente";
    		}else{
    			echo "<p>{$habitacion->cliente->placa}</p>";
    		}
    		
	   		echo "<h2>".$habitacion->estado."</h2>";
    	}
    }
}
