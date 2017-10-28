<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    protected $table = 'personal';
    protected $primaryKey = 'persId';
    protected $fillable = ['persId','persDni','persNombre','persApePaterno','persApeMaterno','persFechaNacimiento','persSexo',
                           'persDireccion','persTelefono01','persTelefono02','persCorreo','persTienda','persEstado'];
    public $timestamps = false;

}
