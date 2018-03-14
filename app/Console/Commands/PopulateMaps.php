<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Httpful\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateMaps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:maps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Maps\'s table from HOTSAPI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Start");
    
        // Starting time
        $startTime = microtime(true);
    
        // Vars
        $baseUri  = "http://hotsapi.net/api/v1/maps";
        $retry    = 0;
        $timedout = false;

        // Disable logs for performance
        DB::connection()->disableQueryLog();
    
        // Call API and retry if fail
        do{
            try {
                $timedout = false;
                $response = Request::get($baseUri)->timeout(120)->send();
            } catch (ConnectionErrorException $e) {
                $timedout = true;
            }
        
            // Retry if call fails
            if((is_object($response) && $response->code != 200 && $response->code != 404) || $timedout){
                $this->info("Retry $baseUri");
                $retry++;
            
                if($timedout)
                    sleep(5);
                else
                    usleep(2500000);
            }
        
        }while($response->code != 200 &&$response->code != 404 && $retry <= 5);
    
        // If API returned code 200
        if($response->code == '200'){
            $this->info("URI : $baseUri");
    
            // JSON Response
            $jsonMaps = $response->body;
    
            // Loop to get all heroes's names
            $sqlMaps   = "INSERT  INTO `hotsinfo`.`maps` (`name`, `created_at`, `updated_at`) VALUES ";
            foreach($jsonMaps as $key => $jsonMap){
                // Wrappers
                $map    = $jsonMap->name;

                if($key > 0){
                    $sqlMaps .= ", ";
                }
                
                // Prepare sql queries
                $sqlMaps .= "(\"$map\", NOW(), NOW())";
            }
            
            // Execute all queries (order of execution is important
            DB::insert($sqlMaps." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
            $this->info("Maps inserted");
        }
        else if($response->code == '404'){
            $this->error("URI : $baseUri");
            $this->error("API didn't response correctly");
            $this->error("Response Code : #".$response->code);
            $this->error("Continue to next replay");
        }
        else{
            $this->error("URI : $baseUri");
            $this->error("API didn't response correctly");
            $this->error("Response Code : #".$response->code);
            die();
        }
    
    
    
        // Ending time
        $timeend = microtime(true);
    
        // Execution time
        $time = round(($timeend - $startTime),0);
        //$time = number_format($time,3);
    
        // Convert seconds to a human readable format
        $c = new Controller();
        $time = $c->secondsToHumanReadableString($time);
    
        $this->info("Script executed in $time");
    
        $this->info("Done");
    }
}
