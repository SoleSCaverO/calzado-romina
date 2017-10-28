<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalPlanilla extends Model
{
    protected $table = 'totalplanilla';
    protected $primaryKey = 'tplaId';
    protected $fillable = ['tplaId','ddatcId','dplaId','ordIdx','tplaCalculado'];
    public $timestamps = false;

}
