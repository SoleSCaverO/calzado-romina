<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlmacenCalzado extends Model
{
    protected $table = 'almacencalzado';
    protected $fillable = ['almId','calId', 'almcalStock'];
    public $timestamps = false;
}
