<?php

namespace App\Console\Commands;

use App\Player;
use App\Http\Controllers\Controller;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulatePlayersWinrates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:players_winrates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate games and victories columns in Players\'s table';

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
     *
     * Take approx. 6 hours
     */
    public function handle()
    {
        $this->info("Start");
    
        // Starting time
        $startTime = microtime(true);
    
        // Enable mass assignment
        Player::unguard();
    
        // Due to the huge amount of players we have to "paginate"
        $maxChunk = 10000;
        $chunkCount = 1;
        Player::chunk($maxChunk, function($players) use(&$chunkCount){
            $this->info("Start of chunk #".$chunkCount);
    
            // Compute winrates for each Player
            foreach($players as $player){
                // Get the stats for a Hero
                $stats = DB::table('participations')
                    ->select(
                        DB::raw('COUNT(1) AS total_games'),
                        DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS total_win')
                    )
                    ->where('player_id', '=', $player->id)
                    ->groupBy('player_id')
                    ->get();
            
                // Update the Hero with stats
                $player->update([
                    'games'     => $stats[0]->total_games,
                    'victories' => $stats[0]->total_win,
                ]);
            
                $this->output->write(".");
            }
            
            $this->output->write(" ", true);
            $this->info("End of chunk #".$chunkCount);
            $chunkCount++;
            
            usleep(500000);
        });
        $this->output->write(" ", true);
    
        // Disable mass assignment
        Player::reguard();
    
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
