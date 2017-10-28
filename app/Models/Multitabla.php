<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multitabla extends Model
{
    protected $table = 'multitabla';
    protected $primaryKey = 'mulId';
    protected $fillable = ['mulId','mulValor', 'mulDepId','mulDescripcion','mulEstado'];
    public $timestamps = false;

}
