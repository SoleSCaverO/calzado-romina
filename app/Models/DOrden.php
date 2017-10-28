<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DOrden extends Model
{
    protected $table = 'dorden';
    protected $primaryKey = 'dordenId';
    protected $fillable = ['dordenId','dordCantidad', 'ordIdx','dprodtalId','dordenEstado'];
    public $timestamps = false;

    protected $appends = ['talla'];

    function getTallaAttribute()
    {
        $dprodtalId = $this->attributes['dprodtalId'];
        return DProdTalla::find($dprodtalId);
    }
}

