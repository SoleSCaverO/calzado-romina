<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleModeloDefecto extends Model
{
    protected $table = 'detalle_modelo_defecto';
    protected $primaryKey = 'moddatosdId'; // Considering fee is the primary key
    protected $fillable = ['moddatosdId','moddatosdEstado','moddatosdPrecio','modId','nivId'];
    public $timestamps = false;
}
