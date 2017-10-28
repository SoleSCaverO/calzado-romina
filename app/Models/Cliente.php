<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'cliId';
    public $timestamps = false;

    protected $fillable = ['cliId','cliTipoCliente', 'cliNombre','cliCodigo','cliTelefono','cliEstado'];
}
