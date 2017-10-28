<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'perId';
    protected $fillable = ['perId','perNombre','perApePaterno','perApeMaterno','perEstado'];
    public $timestamps = false;

}
