<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model 
{

    protected $table = 'versions';
    public $timestamps = true;

    public function games()
    {
        return $this->hasMany('App\Game');
    }

}