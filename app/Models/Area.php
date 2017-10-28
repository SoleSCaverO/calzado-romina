<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';
    protected $primaryKey = 'areId';
    protected $fillable = ['areId','areNombre', 'areDescripcion','areEstado','areFlag','arePerfilado','areProceso'];
    public $timestamps = false;

    public function subareas()
    {
        return $this->hasMany('App\Models\SubArea','areId','areId');
    }
}
