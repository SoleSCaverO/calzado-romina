<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepMaterial extends Model
{
    protected $table = 'repmaterial';
    protected $primaryKey = 'repmatId';
    protected $fillable = ['repmatId','subaId','repmatDescripcion','repmatEstado'];
    public $timestamps = false;

}
