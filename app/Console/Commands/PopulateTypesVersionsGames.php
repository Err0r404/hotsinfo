<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Httpful\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateTypesVersionsGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:types_versions_games {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Type, Versions and Games\'s tables from HOTSAPI';

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
        $file  = $this->argument('file');
        $path  = "storage/app/data/$file";
        $count = 1;
    
        $sqlGames = array();
    
        if(!file_exists($path)){
            $this->error("$path not found");
        }
        else{
            $handle = fopen($path, "r");
            if ($handle) {
                $this->info("Starting inserting Types & Versions");
                
                while (($buffer = fgets($handle)) !== false) {
                    //echo $buffer;
                
                    if($count == 1){
                        $sqlTypes    = "INSERT  INTO `hotsinfo`.`types` (`name`, `created_at`, `updated_at`) VALUES ";
                        $sqlVersions = "INSERT  INTO `hotsinfo`.`versions` (`version`, `created_at`, `updated_at`) VALUES ";
                    }
                
                    // Decode line and get values
                    $data    = json_decode($buffer, true);
                    $type    = $data["game_type"];
                    $version = $data["game_version"];
                    $apiId   = $data["id"];
                    $length  = $data["game_length"];
                    $date    = $data["game_date"];
                    $map     = $data["game_map"];
    
                    if($count > 1){
                        $sqlTypes .= ", ";
                        $sqlVersions .= ", ";
                    }
    
                    $sqlTypes    .= "(\"$type\", NOW(), NOW())";
                    $sqlVersions .= "(\"$version\", NOW(), NOW())";

                    $sqlGames[] = "INSERT INTO `hotsinfo`.`games` (`length`, `date`, `api_id`, `created_at`, `updated_at`, `map_id`, `type_id`, `version_id`)
                                     SELECT \"$length\", \"$date\", \"$apiId\", NOW(), NOW(), maps.id, types.id, versions.id
                                     FROM   maps, types, versions
                                     WHERE  maps.name = \"$map\"
                                            AND types.name = \"$type\"
                                            AND versions.version = \"$version\"
                                 ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
                    
                    $count++;
                    
                    if($count == 101){
                        DB::insert($sqlTypes." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
                        DB::insert($sqlVersions." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
                        $this->output->write(".");
                        
                        $count = 1;
    
                        $sqlTypes    = "";
                        $sqlVersions = "";
                    }
                }
            
                if (!feof($handle)) {
                    $this->error("fgets() failed");
                }
            
                fclose($handle);
                
                if($sqlTypes != "" || $sqlVersions != ""){
                    DB::insert($sqlTypes." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
                    DB::insert($sqlVersions." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
                    $this->output->write(".");
                }
                $this->output->write(" ", true);
                $this->info("Done inserting Types & Versions");
    
                $this->info("Starting inserting Games");
                foreach ($sqlGames as $sqlGame) {
                    DB::insert($sqlGame);
                    $this->output->write(".");
                }
                $this->output->write(" ", true);
                $this->info("Done inserting ".count($sqlGames)." Games");
            }
            else{
                $this->error("Cannot open $path");
            }
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
