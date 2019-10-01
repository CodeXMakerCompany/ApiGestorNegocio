<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';

    
    public function producto(){
    	return $this->belongsTo('App\Producto');
	}
}
