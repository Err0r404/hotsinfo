<?php

namespace App\Console\Commands;

use App\Hero;
use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateHeroesWinrates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:heroes_winrates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate games and victories columns in Heroes\'s table';

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
        Hero::unguard();
        
        // Get all Heroes
        $heroes = Hero::all();
        $this->info("Heroes : ".count($heroes));
    
        // Compute winrates for each Hero
        foreach($heroes as $hero){
            // Get the stats for a Hero
            $stats = DB::table('participations')
                ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
                ->select(
                    DB::raw('COUNT(1) AS total_games'),
                    DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win')
                )
                ->where('participations.hero_id', $hero->id)
                ->groupBy('hero_id')
                ->get();
    
            if(isset($stats[0])){
                // Update the Hero with stats
                $hero->update([
                    'games'     => $stats[0]->total_games,
                    'victories' => $stats[0]->total_win,
                ]);
            }
    
            $this->info("Hero id #".$hero->id." done");
        }
        $this->info(" ");
    
        // Disable mass assignment
        Hero::reguard();
    
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
