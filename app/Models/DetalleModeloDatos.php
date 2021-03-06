<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleModeloDatos extends Model
{
    protected $table = 'detalle_modelo_datos';
    protected $primaryKey = 'moddatosId'; // Considering fee is the primary key
    protected $fillable = [
        'moddatosId','modId', 'ddatcId','moddatosEstado','moddatosCondicion','moddatosPiezas','pieId','description_id'
    ];
    public $timestamps = false;

    public function precio(){
        return $this->belongsTo(DDatosCalculo::class,'ddatcId','ddatcId');
    }
}
