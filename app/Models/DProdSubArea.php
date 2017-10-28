<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DProdSubArea extends Model
{
    protected $table = 'dprodsubarea';
    protected $primaryKey = 'dprodsubId';
    protected $fillable = ['dprodsubId','dprodsubDescripcion', 'dproduccionId','subaId','dprodsubEstado'];
    public $timestamps = false;

    protected $appends = ['subarea'];

    public function getSubareaAttribute()
    {
        $subaId = $this->attributes['subaId'];
        return SubArea::where('subaEstado',1)->where('subaId',$subaId)->first();
    }

    // DprodMateriales
    /*
    public function getMaterialesAttribute()
    {
        $dprodsubId = $this->attributes['dprodsubId'];
        return DProdMat::where('dprodsubId',$dprodsubId)->where('dprodmatEstado',1)->get();
    }
    */
}
