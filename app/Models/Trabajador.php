<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    protected $table = 'trabajador';
    protected $primaryKey = 'traId';
    protected $fillable = ['traId','traDni','traNombre','traApellidos','traEstado','traFlag'];
    public $timestamps = false;
    protected $appends = ['detalles'];

    public function subareas()
    {
        return $this->belongsToMany('App\Models\SubArea','dtrabajador','traId','subaId')
            ->withPivot('dtraEstado');
    }

    public function getDetallesAttribute()
    {
        $traId = $this->attributes['traId'];
        $dtrabajdores = DTrabajador::where('traId',$traId)->get();

        return $dtrabajdores;
    }

    function area_subarea( $trabajador_id )
    {
        $dtrabajador = DTrabajador::where('traId',$trabajador_id)->first();
        $subarea_menor = SubareaMenor::find($dtrabajador->subamId);
        $subarea = $subarea_menor->subarea->subaDescripcion;
        $area    = $subarea_menor->subarea->area->areNombre;

        return ['area'=>$area,'subarea'=>$subarea];
    }
}
