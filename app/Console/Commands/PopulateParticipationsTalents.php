<?php

namespace App\Console\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PopulateParticipationsTalents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:participations_talents {file}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate ParticipationTalent\'s table from HOTSAPI';
    
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
        // Take about 2 days 12h/file

        ini_set('memory_limit', '2048M');
        $m = ini_get('memory_limit');
        $this->info("Memory limit $m");
        
        $this->info("Start");
        
        // Starting time
        $startTime = microtime(true);
        
        // Vars
        $file  = $this->argument('file');
        $path  = "storage/app/data/$file";
        $count = 1;
    
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
                        $talents    = $player["talents"];
                        $blizzardId = $player["blizz_id"];
    
                        if(is_array($talents)){
                            foreach ($talents as $talent) {
                                $sqlParticipationsTalents[] = "INSERT INTO `hotsinfo`.`participation_talent` (`created_at`, `updated_at`, `participation_id`, `talent_id`)
                                         SELECT NOW(), NOW(), participations.id, talents.id
                                         FROM   participations, games, players, talents
                                         WHERE  participations.game_id = games.id
                                                AND participations.player_id = players.id
                                                AND games.api_id = \"$apiId\"
                                                AND players.blizzard_id = \"$blizzardId\"
                                                AND talents.reference = \"$talent\"
                                     ON DUPLICATE KEY UPDATE `updated_at` = NOW()";
                            }
                        }
    
                        $talents    = null;
                        $blizzardId = null;
                        unset($talents);
                        unset($blizzardId);
                    }
    
                    $data    = null;
                    $apiId   = null;
                    $players = null;
                    unset($data);
                    unset($apiId);
                    unset($players);
                    
                    // If array is over 1 million executes requests to prevent excessive memory use
                    if(count($sqlParticipationsTalents) > 1000000){
                        $this->info("Starting inserting ".count($sqlParticipationsTalents)." ParticipationsTalents");
                        foreach ($sqlParticipationsTalents as $sqlParticipationTalent) {
                            DB::insert($sqlParticipationTalent);
                            $this->output->write(".");
                        }
                        $this->output->write(" ", true);
                        $this->info("Done inserting ".count($sqlParticipationsTalents)." ParticipationsTalents");
        
                        $sqlParticipationsTalents = array();
                    }
                }
                
                $this->info("Done reading file");
                $this->info(count($sqlParticipationsTalents)." ParticipationsTalents");
                
                if (!feof($handle)) {
                    $this->error("fgets() failed");
                }
                
                fclose($handle);
                
                $this->info("Starting inserting remaining ParticipationsTalents");
                foreach ($sqlParticipationsTalents as $sqlParticipationTalent) {
                    DB::insert($sqlParticipationTalent);
                    $this->output->write(".");
                }
                $this->output->write(" ", true);
                $this->info("Done inserting ".count($sqlParticipationsTalents)." ParticipationsTalents");
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
