<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model 
{

    protected $table = 'maps';
    public $timestamps = true;

    public function games()
    {
        return $this->hasMany('App\Game');
    }

}