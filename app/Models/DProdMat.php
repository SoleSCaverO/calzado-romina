<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DProdMat extends Model
{
    protected $table = 'dprodmat';
    protected $primaryKey = 'dprodmatId';
    protected $fillable = ['dprodmatId','dprodsubId', 'smatId','dprodmatEstado'];
    public $timestamps = false;

    protected $appends = ['material'];

    public function getMaterialAttribute()
    {
        $smatId = $this->attributes['smatId'];
        return SMaterial::where('smatId',$smatId)->where('smatEstado',1)->first();
    }

}
