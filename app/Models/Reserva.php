<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reserva';
    protected $primaryKey = 'resId';
    protected $fillable = ['resId','persId','resFecha','almId','calId','resCantidad','resEnviado','resObservacion','resEstado'];
    public $timestamps = false;

}
