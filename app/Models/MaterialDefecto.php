<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialDefecto extends Model
{
    protected $table = 'materialdefecto';
    protected $primaryKey = 'matdId';
    protected $fillable = ['matdId','matdTipo', 'matdNombre','matdEstado'];
    public $timestamps = false;

}
