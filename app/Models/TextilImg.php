<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextilImg extends Model
{
    protected $table = 'textilimg';
    protected $primaryKey = 'texId';
    protected $fillable = ['texId','texImagen','smatId'];
    public $timestamps = false;

}
