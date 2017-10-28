<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $table = 'kardex';
    protected $primaryKey = 'karId';
    protected $fillable = ['karId','almId', 'calId','karOperacion','karFechaOperacion','karCantidad','karObservacion',
                           'karDetalle','persId'];
    public $timestamps = false;

}
