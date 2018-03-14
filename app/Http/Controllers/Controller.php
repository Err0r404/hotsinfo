<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    /**
     * Convert seconds to a human readable string
     *
     * @param int $seconds
     *
     * @return string
     * @internal param int $seconds
     *
     */
    function secondsToHumanReadableString(int $seconds){
        if($seconds > 59){
            $m = floor(($seconds%3600)/60);
            $h = floor(($seconds%86400)/3600);
            $d = floor(($seconds)/86400);
            
            $result = "";
            
            if($d > 0)
                $result.= "$d days";
            
            if($result != "")
                $result .= ", ";
            
            if($h > 0)
                $result.= "$h hours";
            
            if($result != "")
                $result .= ", ";
            
            if($m > 0)
                $result.= "$m minutes";
        }
        else{
            $result = "$seconds seconds";
        }
        
        return $result;
    }
    
}
