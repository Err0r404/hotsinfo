<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talent extends Model 
{

    protected $table = 'talents';
    public $timestamps = true;

    public function hero()
    {
        return $this->belongsTo('App\Hero');
    }

    public function participations()
    {
        return $this->belongsToMany('App\Participation');
    }

}