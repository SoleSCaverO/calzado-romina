<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DPlanilla extends Model
{
    protected $table = 'dplanilla';
    protected $primaryKey = 'dplaId';
    protected $fillable = ['dplaId','plaId', 'dtraId','subaId','traId','dplaEstado'];
    public $timestamps = false;

}
