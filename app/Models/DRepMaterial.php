<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DRepMaterial extends Model
{
    protected $table = 'drepmaterial';
    protected $primaryKey = 'drepmatId';
    protected $fillable = ['drepmatId','ordIdx', 'repmatId','drepmatRecibido'];
    public $timestamps = false;

}
