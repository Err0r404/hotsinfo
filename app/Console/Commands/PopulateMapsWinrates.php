<?php

namespace App\Console\Commands;

use App\Map;
use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateMapsWinrates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:maps_winrates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate games column in Maps\'s table';

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
    
        // Enable mass assignment
        Map::unguard();
    
        // Get all Maps
        $maps = Map::all();
        $this->info("Maps : ".count($maps));
    
        // Compute winrates for each Hero
        foreach($maps as $map){
            // Get the stats for a Hero
            $stats = DB::table('participations')
                ->join('games', 'participations.game_id', '=', 'games.id')
                ->join('maps', 'games.map_id', '=', 'maps.id')
                ->select(
                    DB::raw('COUNT(1) AS total_games')
                )
                ->where('maps.id', $map->id)
                ->groupBy('maps.id')
                ->get();
            
            if(isset($stats[0])){
                // Update the Hero with stats
                $map->update([
                    'games' => $stats[0]->total_games,
                ]);
            }
            
            $this->info("Map id #".$map->id." done");
        }
        $this->info(" ");
    
        // Disable mass assignment
        Map::reguard();
    
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
