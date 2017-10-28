<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DTrabajador extends Model
{
    protected $table = 'dtrabajador';
    protected $primaryKey = 'dtraId';
    protected $fillable = ['dtraId','subamId', 'traId','dtraEstado','dtraCodigobarras','dtraSueldo'];
    public $timestamps = false;

    protected $appends = ['tipo_sueldo'];

    public function getTipoSueldoAttribute()
    {
        $tipoSueldo = $this->attributes['dtraSueldo'];
        return ($tipoSueldo==1)?'Fijo':'Destajo';
    }

}
