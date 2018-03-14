<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model 
{

    protected $table = 'heroes';
    public $timestamps = true;

    public function talents()
    {
        return $this->hasMany('App\Talent');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function roles()
    {
        return $this->belongsTo('App\Role');
    }

}