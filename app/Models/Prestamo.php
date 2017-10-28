<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $table = 'prestamo';
    protected $primaryKey = 'preId';
    protected $fillable = ['preId','persId','calId','almId','preParteCalzado','preFechaPrestamo','preFechaDevolucion',
                           'preObservacion','preEstado'];
    public $timestamps = false;

}
