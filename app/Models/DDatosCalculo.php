<?php

namespace App\Models;

use App\Reserva;
use Illuminate\Database\Eloquent\Model;

class DDatosCalculo extends Model
{
    protected $table = 'ddatoscalculo';
    protected $primaryKey = 'ddatcId'; // Considering fee is the primary key
    protected $fillable = ['ddatcId','tipoPrecio','ddatcDescripcion','ddatcNombre','ddatcPrecioDocena',
                           'ddatcCondicion','ddatcPrecioCondicion','ddatcFlag','ddatcEstado','ddatcDatoCondicion',
                           'ddatcMayorCondicion','pieId','nivId'];
    public $timestamps = false;

    protected $appends = ['pieza'];

    public function getPiezaAttribute()
    {
        $pieza_id = @$this->attributes['pieId'];
        if( is_null($pieza_id) )
            return '';

        $pieza = Pieza::find($pieza_id);
        return $pieza->pieTipo;
    }

    public function nivel()
    {
         return $this->belongsTo('App\Models\Nivel','nivId','nivId');
    }
}
