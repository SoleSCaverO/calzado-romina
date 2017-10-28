<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoCalculo extends Model
{
    protected $table = 'tipocalculo';
    protected $primaryKey = 'tcalId';
    protected $fillable = ['tcalId','tcalDescripcion','tcalEstado','tcalTipo','tcalFlag'];
    public $timestamps = false;

    public function subarea()
    {
        return $this->belongsToMany('App\SubArea','dsubatipoc','tipocalId','subaId');
    }
}
