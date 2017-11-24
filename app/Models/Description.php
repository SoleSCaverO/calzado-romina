<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $fillable = ['name','description','state'];

    public function niveles(){
        return $this->hasMany('App\Models\Nivel');
    }
}
