<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'usuId';
    protected $fillable = ['usuId','persId','usuUsuario','usuClave','usuEstado'];
    public $timestamps = false;

}
