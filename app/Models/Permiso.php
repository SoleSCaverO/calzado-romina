<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = 'permId';
    protected $fillable = ['permId','menId','rolId'];
    public $timestamps = false;

}
