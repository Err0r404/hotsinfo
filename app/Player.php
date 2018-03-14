<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model 
{

    protected $table = 'players';
    public $timestamps = true;

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

}