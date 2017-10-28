<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    protected $table = 'cronograma';
    protected $primaryKey = 'cuotaId'; // Considering fee is the primary key
    protected $fillable = ['cuotaId','venId', 'croFechaVencimiento','croMonto','croMontoCancelado','croEstado'];
    public $timestamps = false;

}
