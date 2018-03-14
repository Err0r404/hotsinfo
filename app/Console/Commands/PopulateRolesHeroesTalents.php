<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Httpful\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateRolesHeroesTalents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:roles_heroes_talents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Roles, Heroes and Talents\'s tables from HOTSAPI';

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
        $baseUri  = "http://hotsapi.net/api/v1/heroes";
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
            $jsonHeroes = $response->body;
    
            // Loop to get all heroes's names
            $sqlRoles   = "INSERT  INTO `hotsinfo`.`roles` (`name`, `created_at`, `updated_at`) VALUES ";
            $sqlHeroes  = array();
            $sqlTalents = array();
            foreach($jsonHeroes as $key => $jsonHero){
                // Wrappers
                $hero    = $jsonHero->name;
                $role    = $jsonHero->role;
                $talents = $jsonHero->talents;

                if($key > 0){
                    $sqlRoles .= ", ";
                }
                
                // Prepare sql queries
                $sqlRoles .= "(\"$role\", NOW(), NOW())";
                
                $sqlHeroes[] = "INSERT INTO `hotsinfo`.`heroes` (`name`, `created_at`, `updated_at`, `role_id`)
                                     SELECT \"$hero\", NOW(), NOW(), id
                                     FROM   roles
                                     WHERE  roles.name = \"$role\"
                                 ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
    
                foreach ($talents as $talent) {
                    $sqlTalents[] = "INSERT INTO `hotsinfo`.`talents` (`name`, `reference`, `description`, `level`, `created_at`, `updated_at`, `hero_id`)
                                     SELECT \"$talent->title\", \"$talent->name\", \"$talent->description\", \"$talent->level\", NOW(), NOW(), id
                                     FROM   heroes
                                     WHERE  heroes.name = \"$hero\"
                                     ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
                }
            }
            
            // Execute all queries (order of execution is important
            DB::insert($sqlRoles." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
            $this->info("Roles inserted");
            
            foreach ($sqlHeroes as $sqlHero) {
                DB::insert($sqlHero);
            }
            $this->info(count($sqlHeroes)." Heroes inserted");

            foreach ($sqlTalents as $sqlTalent) {
                DB::insert($sqlTalent);
            }
            $this->info(count($sqlTalents)." Talents inserted");
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
