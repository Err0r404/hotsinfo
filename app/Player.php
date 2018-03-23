<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Player extends Model {
    
    protected $table = 'players';
    public $timestamps = true;
    
    use Searchable;
    
    public function participations() {
        return $this->hasMany('App\Participation');
    }
    
    public function toSearchableArray() {
        // Will search only for its battletag field
        return $this->only('battletag');
    }
    
}