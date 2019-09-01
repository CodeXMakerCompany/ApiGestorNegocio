<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = "habitacion";

    public function cliente(){
    	return $this->hasOne('App\Cliente');
	}

}
