<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DProdTalla extends Model
{
    protected $table = 'dprodtalla';
    protected $primaryKey = 'dprodtalId';
    protected $fillable = ['dprodtalId','dprodTalCantidad', 'calId','dprodTalTalla','dprodId'];
    public $timestamps = false;

}
