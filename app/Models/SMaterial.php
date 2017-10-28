<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SMaterial extends Model
{
    protected $table = 'smaterial';
    protected $primaryKey = 'smatId';
    protected $fillable = ['smatId','subaId','smatDescripcion','smatFlag','smatTextil','smatModelod','smatColord','matdId'];
    public $timestamps = false;

    protected $appends = ['material_defecto'];

    public function materialdefecto()
    {
        return $this->belongsTo('App\Models\MaterialDefecto','matdId','matdId');
    }


}
