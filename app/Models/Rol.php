<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'rolId';
    protected $fillable = ['rolId','rolNombre','rolActivo','rolPrivado'];
    public $timestamps = false;

}
