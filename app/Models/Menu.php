<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'menId';
    protected $fillable = ['menId','menPadreId', 'menNombre','menOrden','menDescripcion','menDraggable','menHidden'];
    public $timestamps = false;

}
