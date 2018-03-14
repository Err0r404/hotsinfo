<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model 
{

    protected $table = 'games';
    public $timestamps = true;

    public function map()
    {
        return $this->belongsTo('App\Map');
    }

    public function type()
    {
        return $this->belongsTo('App\Type');
    }

    public function version()
    {
        return $this->belongsTo('App\Version');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

}