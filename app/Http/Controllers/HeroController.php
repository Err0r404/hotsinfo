<?php

namespace App\Http\Controllers;

use App\Talent;
use Illuminate\Http\Request;
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
                'heroes.games as readable_games',
                DB::raw('ROUND((victories/games)*100,2) AS winrate'),
                'roles.id as role_id',
                'roles.name as role'
        )
            ->where('heroes.id', $id)
            ->get();
        
        foreach ($heroes as $hero) {
            $hero->slug  = str_slug($hero->name);
            $hero->readable_games = $this->numbertoHumanReadableFormat($hero->games);
        }
        
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
            ->get();

        // Group talents by levels
        $list = [];
        foreach ($talents as $talent) {
            $list[$talent->level][] = $talent;
        }
        $talents = $list;
    
        // Calculate popularity of each talent by level
        foreach ($talents as $talentsList) {
            $sumPick = array_sum(array_column($talentsList, 'pick'));
    
            foreach ($talentsList as $talent) {
                $talent->popularity = round(($talent->pick/$sumPick)*100,2);
            }
        }
    
        return view(
            'heroes.show',
            [
                'hero'    => $heroes[0],
                'talents' => $talents,
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
    
}

?>