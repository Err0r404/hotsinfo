<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HeroController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $heroes = DB::table('heroes')
            ->select(
                'heroes.id',
                'heroes.name',
                'heroes.games',
                'heroes.games as readable_games',
                DB::raw('ROUND((victories/games)*100,2) AS winrate')
            )
            ->orderBy('heroes.name')
            ->get();
    
        foreach ($heroes as $hero) {
            $hero->slug  = str_slug($hero->name);
            $hero->readable_games = $this->numbertoHumanReadableFormat($hero->games);
        }
        
        return view('heroes.index', ['heroes' => $heroes]);
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id) {
        // Get the Hero
        $heroes = DB::table('heroes')
            ->join('roles', 'heroes.role_id', '=', 'roles.id')
            ->select(
                'heroes.id',
                'heroes.name',
                'heroes.games',
                DB::raw('ROUND((victories/games)*100,2) AS winrate'),
                'roles.id as role_id',
                'roles.name as role'
        )
            ->where('heroes.id', $id)
            ->get();
        
        // Format some data
        foreach ($heroes as $hero) {
            $hero->slug  = str_slug($hero->name);
            $hero->readable_games = $this->numbertoHumanReadableFormat($hero->games);
        }
        $hero = $heroes['0'];
        
        // Get talents
        $talents = DB::table('participation_talent')
            ->join('talents', 'talents.id', '=', 'participation_talent.talent_id')
            ->select(
                'talents.id',
                'talents.name',
                'talents.description',
                'talents.level',
                DB::raw('COUNT(1) as pick')
            )
            ->where('talents.hero_id', $id)
            ->groupBy('participation_talent.talent_id')
            ->orderBy('talents.level')
            ->get();

        // Group talents by levels
        $list = [];
        foreach ($talents as $talent) {
            $list[$talent->level][] = $talent;
        }
        $talents = $list;
        
        // Sort talents by their levels
        ksort($talents);
    
        // Calculate popularity of each talent by level
        foreach ($talents as $talentsList) {
            $sumPick = array_sum(array_column($talentsList, 'pick'));
    
            foreach ($talentsList as $talent) {
                $talent->popularity = round(($talent->pick/$sumPick)*100,2);
            }
        }
    
        // Get enemies from Cache
        $enemies = Cache::get($id.'-enemies', []);
        
        // Get enemies from Cache
        $allies = Cache::get($id.'-allies', []);
        
        return view(
            'heroes.show',
            [
                'hero'    => $hero,
                'talents' => $talents,
                'enemies' => $enemies,
                'allies'  => $allies,
            ]
        );
    
    }
    
    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enemies($id){
        // Get Heroes stats against the chosen Hero
        $enemies = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS games'),
                //DB::raw("'All games' AS type"),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS winrate')
            )
            ->where('participations.hero_id', '=', $id)
            ->where('participations.win', '<>', DB::raw('`p2`.`win`'))
            ->where('participations.id', '<>', DB::raw('`p2`.`id`'))
            ->where('p2.hero_id', '<>', $id)
            ->groupBy('heroes.id')
            ->orderBy('games', 'desc')
            ->orderBy('winrate', 'desc')
            ->get();
    
        foreach ($enemies as $enemy){
            $enemy->slug  = str_slug($enemy->name);
            $enemy->readable_games = $this->numbertoHumanReadableFormat($enemy->games);
        }
    
        $expiresAt = now()->addDays(7);
        Cache::put($id.'-enemies', $enemies, $expiresAt);
        
        return response()->json($enemies);
    }
    
    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allies($id){
        // Get Heroes stats with the chosen Hero
        $allies = DB::table('participations')
            ->join('games', 'participations.game_id', '=', 'games.id')
            ->join('participations as p2', 'p2.game_id', '=', 'games.id')
            ->join('heroes', 'p2.hero_id', '=', 'heroes.id')
            ->select(
                'heroes.id',
                'heroes.name',
                DB::raw('COUNT(1) AS games'),
                //DB::raw("'All games' AS type"),
                DB::raw('SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END) AS win'),
                DB::raw('ROUND((SUM(CASE WHEN p2.win = 1 THEN 1 ELSE 0 END)/COUNT(1))*100,2) AS winrate')
            )
            ->where('participations.hero_id', '=', $id)
            ->where('participations.win', '=', DB::raw('`p2`.`win`'))
            ->where('participations.id', '<>', DB::raw('`p2`.`id`'))
            ->where('p2.hero_id', '<>', $id)
            ->groupBy('heroes.id')
            ->orderBy('games', 'desc')
            ->orderBy('winrate', 'desc')
            ->get();
    
        foreach ($allies as $ally){
            $ally->slug  = str_slug($ally->name);
            $ally->readable_games = $this->numbertoHumanReadableFormat($ally->games);
        }
    
        $expiresAt = now()->addDays(7);
        Cache::put($id.'-allies', $allies, $expiresAt);
        
        return response()->json($allies);
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
    
}

?>