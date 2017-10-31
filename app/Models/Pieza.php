<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    protected $table = 'pieza';
    protected $primaryKey = 'pieId';
    protected $fillable = [
        'pieId','pieMultiplo','pieDescripcion','pieTipo','pieEstado','pieInicial','pieFinal','pieFlag','description_id'
    ];
    public $timestamps = false;

}
