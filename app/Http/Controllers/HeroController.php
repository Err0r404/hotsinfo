<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeroController extends Controller {
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
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
     * @return Response
     */
    public function show($id) {

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