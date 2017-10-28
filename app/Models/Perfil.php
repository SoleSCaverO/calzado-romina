<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil';
    protected $primaryKey = 'perfId';
    protected $fillable = ['perfId','rolId','usuId'];
    public $timestamps = false;

}
