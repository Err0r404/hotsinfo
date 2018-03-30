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
                DB::raw('ROUND((victories/games)*100, 0) AS winrate'),
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
            ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
            ->join('maps', 'games.map_id', '=', 'maps.id')
            ->select(
                'games.id',
                'games.length',
                'games.date',
                'maps.id as map_id',
                'maps.name as map',
                'participations.win',
                'heroes.name as hero'
            )
            ->where('participations.player_id', $id)
            ->orderby('games.date', 'desc')
            ->paginate(10);
        
        // Reformat games to group players per parties : 1 game with 10 players instead of 10 games with 1 player
        foreach ($games as $key => $game) {
            // Pretty format some data
            $game->readable_length = $this->secondsToHumanReadableString($game->length);
            $game->ago = $this->datetimeToTimeAgo($game->date);

            // Get all players in game
            $teammates = DB::table('participations')
                ->join('players', 'participations.player_id', '=', 'players.id')
                ->join('heroes', 'participations.hero_id', '=', 'heroes.id')
                ->select(
                    'players.id as player_id',
                    'players.battletag',
                    'participations.silenced',
                    'participations.team',
                    'heroes.id as hero_id',
                    'heroes.name as hero'
                )
                ->where('participations.game_id', '=', $game->id)
                ->get();
            
            // Add those players to the game
            $game->teammates = $teammates;
            
            // Get the talents
            $talents = DB::table('participations')
                ->join('participation_talent as pt', 'participations.id', '=', 'pt.participation_id')
                ->join('talents', 'pt.talent_id', '=', 'talents.id')
                ->select(
                    'talents.id',
                    'talents.name',
                    'talents.description'
                )
                ->where('participations.game_id', '=', $game->id)
                ->where('participations.player_id', '=', $id)
                ->get();
            
            // Add those talents to the game
            $game->talents = $talents;
        }
        
        $mostPlayedHeroes = DB::table('participations')
            ->join('heroes', 'participations.hero_id', 'heroes.id')
            ->select(
                'heroes.name',
                'heroes.id',
                DB::raw('COUNT(1) AS games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS win'),
                DB::raw('ROUND(((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END))/(COUNT(1)))*100, 0) AS winrate')
            )
            ->where('participations.player_id', $id)
            ->groupBy('participations.hero_id')
            ->orderby('games', 'desc')
            ->get();
    
        foreach ($mostPlayedHeroes as $hero) {
            $hero->slug = str_slug($hero->name);
        }
        
        $mostPlayedMaps = DB::table('participations')
            ->join('games', 'participations.game_id', 'games.id')
            ->join('maps', 'games.map_id', 'maps.id')
            ->select(
                'maps.id',
                'maps.name',
                DB::raw('COUNT(1) AS games'),
                DB::raw('SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END) AS win'),
                DB::raw('ROUND(((SUM(CASE WHEN win = 1 THEN 1 ELSE 0 END))/(COUNT(1)))*100, 0) AS winrate')
            )
            ->where('participations.player_id', $id)
            ->groupBy('games.map_id')
            ->orderby('games', 'desc')
            ->get();
    
        foreach ($mostPlayedMaps as $map) {
            $map->slug = str_slug($map->name);
        }
    
        return view(
            'players.show',
            [
                'player' => $player,
                'games'  => $games,
                'heroes' => $mostPlayedHeroes,
                'maps'   => $mostPlayedMaps,
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