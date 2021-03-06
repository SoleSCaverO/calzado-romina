<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DSubATipoC extends Model
{
    protected $table = 'dsubatipoc';
    protected $primaryKey = 'dsatipocal';
    protected $fillable = ['dsatipocal','subamId', 'tipocalId'];
    public $timestamps = false;


    public function subarea_menor(){
        return $this->belongsTo(SubareaMenor::class,'subamId','subamId');
    }
}
