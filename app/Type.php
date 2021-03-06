<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model 
{

    protected $table = 'types';
    public $timestamps = true;

    public function games()
    {
        return $this->hasMany('App\Game');
    }

}