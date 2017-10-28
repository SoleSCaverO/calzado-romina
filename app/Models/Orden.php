<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    protected $table = 'orden';
    protected $primaryKey = 'ordIdx';
    protected $fillable = ['ordIdx','ordCantidad','ordCodigo', 'ordId','prodId','ordModelod','ordColord'];
    public $timestamps = false;

    protected $appends =['produccion','detalles','genero','modelo','imagenes'];

    function getProduccionAttribute()
    {
        $prodId = $this->attributes['prodId'];
        return Produccion::find($prodId);
    }

    function getDetallesAttribute()
    {
        $ordIdx = $this->attributes['ordIdx'];
        return DOrden::where('ordIdx',$ordIdx)->get();
    }

    public function getGeneroAttribute()
    {
        $modId = $this->attributes['ordModelod'];
        $modelo = Modelo::find($modId);

        return $modelo->modGenero;
    }

    public function getModeloAttribute()
    {
        $modId = $this->attributes['ordModelod'];
        $modelo = Modelo::find($modId);

        return $modelo->modDescripcion;
    }

    public function getImagenesAttribute()
    {
        $modId = $this->attributes['ordModelod'];
        return MImagen::where('modId',$modId)->where('imgEstado',1)->get();
    }
}
