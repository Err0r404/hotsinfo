<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PopulateParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:participations {file}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate Participation\'s table from HOTSAPI';
    
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
        ini_set('memory_limit', '1024M');
        $m = ini_get('memory_limit');
        $this->info("Memory limit $m");
        
        $this->info("Start");
        
        // Starting time
        $startTime = microtime(true);
        
        // Vars
        $file  = $this->argument('file');
        $path  = "storage/app/data/$file";
        $count = 1;
    
        $sqlParticipations = array();
        $sqlParticipationsTalents = array();
        
        if(!file_exists($path)){
            $this->error("$path not found");
        }
        else{
            $handle = fopen($path, "r");
            if ($handle) {
                while (($buffer = fgets($handle)) !== false) {
                    //echo $buffer;
                    
                    // Wrappers
                    $data    = json_decode($buffer, true);
                    $apiId   = $data["id"];
                    $players = $data["players"];
    
                    foreach ($players as $player) {
                        $hero       = $player["hero"];
                        $heroLevel  = $player["hero_level"];
                        $win        = $player["winner"];
                        $team       = $player["team"];
                        $silenced   = $player["silenced"];
                        $blizzardId = $player["blizz_id"];
    
                        $win      = ($win === true) ? 1 : 0;
                        $silenced = ($silenced === true) ? 1 : 0;
                        
                        $sqlParticipations[] = "INSERT INTO `hotsinfo`.`participations` (`hero_level`, `win`, `team`, `silenced`, `created_at`, `updated_at`, `player_id`, `hero_id`, `game_id`)
                                     SELECT \"$heroLevel\", \"$win\", \"$team\", \"$silenced\", NOW(), NOW(), players.id, heroes.id, games.id
                                     FROM   players, heroes, games
                                     WHERE  players.blizzard_id = \"$blizzardId\"
                                            AND heroes.name = \"$hero\"
                                            AND games.api_id = \"$apiId\"
                                 ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
                    }
                }
                $this->info("Done reading file");
                $this->info(count($sqlParticipations)." Participations");
                
                if (!feof($handle)) {
                    $this->error("fgets() failed");
                }
                
                fclose($handle);
                
                $this->info("Starting inserting Participations");
                foreach ($sqlParticipations as $sqlParticipation) {
                    DB::insert($sqlParticipation);
                    $this->output->write(".");
                }
                $this->output->write(" ", true);
                $this->info("Done inserting ".count($sqlParticipations)." Participations");
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
