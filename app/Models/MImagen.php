<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MImagen extends Model
{
    protected $table = 'mimagen';
    protected $primaryKey = 'imgId';
    protected $fillable = ['imgId','modId', 'imgDescripcion','imgEstado','imgActivo'];
    public $timestamps = false;

    function modelo()
    {
        return $this->belongsTo('App\Models\Modelo','modId','modId');
    }
}
