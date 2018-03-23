<?php

namespace App\Http\Controllers;

use App\Player;
use Illuminate\Http\Request;

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