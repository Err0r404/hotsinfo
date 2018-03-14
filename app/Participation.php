<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model 
{

    protected $table = 'participations';
    public $timestamps = true;

    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function hero()
    {
        return $this->belongsTo('App\Hero');
    }

    public function player()
    {
        return $this->belongsTo('App\Player');
    }

    public function talents()
    {
        return $this->belongsToMany('App\Talent');
    }

}