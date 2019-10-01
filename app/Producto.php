<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

    protected $fillable = ['id_categoria','descripcion','cantidad','nombre','precio','tipo','multimedia'];

    public function categoria(){
    	return $this->belongsTo('App\Categoria','id_categoria');
	}
}
