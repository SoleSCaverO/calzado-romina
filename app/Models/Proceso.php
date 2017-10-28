<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    protected $table = 'proceso';
    protected $primaryKey = 'proId';
    protected $fillable = ['proId','subaId','proNombre','proEstado','proFlag','proDescripcion'];
    public $timestamps = false;

}
