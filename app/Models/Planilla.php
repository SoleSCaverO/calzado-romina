<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Planilla extends Model
{
    protected $table = 'planilla';
    protected $primaryKey = 'plaId';
    protected $fillable = ['plaId','plaFechaInicio','plaFechaFin','plaEstado','plaFlag'];
    public $timestamps = false;

    protected $appends = ['fecha_inicio','fecha_fin'];


    function getFechaInicioAttribute()
    {
        $plaFechaInicio = $this->attributes['plaFechaInicio'];
        $fecha = new Carbon($plaFechaInicio);
        $fechaInicio = $fecha->format('d-m-Y');

        $hora  = new Carbon($plaFechaInicio);
        $horaInicio = $hora->format('g:i A');

        return $fechaInicio.' '.$horaInicio;
    }
    function getFechaFinAttribute()
    {
        $plaFechaFin = $this->attributes['plaFechaFin'];
        $fecha = new Carbon($plaFechaFin);
        $fechaFin = $fecha->format('d-m-Y');

        $hora  = new Carbon($plaFechaFin);
        $horaFin = $hora->format('g:i A');

        return $fechaFin.' '.$horaFin;
    }
}
