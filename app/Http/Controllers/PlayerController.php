<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
    
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
    
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request) {
    
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id) {
        // Get the Hero
        $players = DB::table('players')
            ->join('participations', 'players.id', '=', 'participations.player_id')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->select(
                'players.id',
                'players.battletag',
                'players.games',
                DB::raw('ROUND((victories/games)*100,2) AS winrate'),
                DB::raw('SUM(games.length) AS length')
            )
            ->where('players.id', $id)
            ->groupBy('player_id')
            ->get();
    
        // Format some data
        foreach ($players as $player) {
            $player->readable_games  = $this->numbertoHumanReadableFormat($player->games);
            $player->readable_length = $this->secondsToHumanReadableString($player->length);
        }
        
        $player = $players['0'];
        
        $games = DB::table('games')
            ->join('participations', 'games.id', '=', 'participations.game_id')
            ->join('participations as p2', 'games.id', '=', 'p2.game_id')
            ->join('players', 'p2.player_id', '=', 'players.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'games.id',
                'games.length',
                'games.date',
                'maps.id as map_id',
                'maps.name as map',
                'participations.win',
                'p2.silenced',
                'p2.team',
                'players.id as player_id',
                'players.battletag',
                'heroes.id as hero_id',
                'heroes.name as hero_name'
            )
            ->where('participations.player_id', $id)
            ->orderby('games.date', 'desc')
            ->paginate(100); // 10 players per party so 100 means 10 parties
    
        $previousGameId = null;
        $firstKey = null;
        
        foreach ($games as $key => $game) {
            
            if($game->id !== $previousGameId){
                // Create an array to store all players
                $game->teammates = [];
                
                // Remember some vars
                $previousGameId = $game->id;
                $firstKey = $key;
            }
            else{
                unset($games[$key]);
            }
    
            // Create a new player using known data
            $teammate            = new \stdClass();
            $teammate->player_id = $game->player_id;
            $teammate->battletag = $game->battletag;
            $teammate->silenced  = $game->silenced;
            $teammate->team      = $game->team;
            $teammate->hero_id   = $game->hero_id;
            $teammate->hero_slug = str_slug($game->hero_name);
            $teammate->hero      = $game->hero_name;
            
            // Add the player to an array
            $games[$firstKey]->teammates[] = $teammate;
            
            // Purge data
            unset($game->battletag);
            unset($game->player_id);
            unset($game->hero_name);
            unset($game->hero_id);
            unset($game->silenced);
            unset($game->team);
            
            // Format some data
            $game->readable_length = $this->secondsToHumanReadableString($game->length);
            $game->ago = $this->datetimeToTimeAgo($game->date);
        }
        
        return view(
            'players.show',
            [
                'player' => $player,
                'games'  => $games,
            ]
        );
    
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id) {
    
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function update($id) {
    
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id) {
    
    }
    
    public function search(Request $request){
        $error = ['error' => 'No player found, please try with different name or battletag'];
        
        if($request->has('player')){
            $player = $request->get('player');
    
            // If user search a battletag
            if(preg_match("/(.*)#[0-9]{4}$/", $player, $output)) {
                $players = Player::where('battletag', $player)->get();
            }
            else{
                $players = Player::search($player)->get();
            }
    
            // TODO : Find player's region because battletag is not unique across region
    
            return view(
                'players.search',
                [
                    'player'  => $player,
                    'players' => $players,
                ]
            );
        }
    
        return view('players.index', $error);
    }
    
}

?>