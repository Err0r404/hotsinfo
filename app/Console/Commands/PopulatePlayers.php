<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PopulatePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:players {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Players\'s table from HOTSAPI';

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
        // Take about 1h/file
        
        $this->info("Start");
    
        // Starting time
        $startTime = microtime(true);
        
        // Vars
        $file  = $this->argument('file');
        $path  = "storage/app/data/$file";
        $count = 1;
        
        if(!file_exists($path)){
            $this->error("$path not found");
        }
        else{
            $handle = fopen($path, "r");
            if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                    //echo $buffer;
                    
                    $sql = "INSERT  INTO `hotsinfo`.`players` (`battletag`, `blizzard_id`, `created_at`, `updated_at`) VALUES ";
                    
                    // Decode line and get players
                    $data    = json_decode($buffer, true);
                    $players = $data["players"];
                    
                    // Loop trough players
                    foreach($players as $key => $player) {
                        $blizzardId = $player['blizz_id'];
                        $battletag = $player['battletag'];
                        
                        if($key > 0){
                            $sql .= ", ";
                        }
    
                        $sql .= "(\"$battletag\", \"$blizzardId\", NOW(), NOW())";
                    }
    
                    DB::insert($sql." ON DUPLICATE KEY UPDATE `updated_at` = NOW()");
                    $this->info("Players inserted (line #$count)");
    
                    $count++;
                }
                
                if (!feof($handle)) {
                    $this->error("fgets() failed");
                }
                
                fclose($handle);
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
