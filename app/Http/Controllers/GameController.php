<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{

    /**
     * Lists games
     *
     * @return void
     */
    public function index(){
        $games = Game::all();
        return response()->json($games , 200);
    }
    /**
     * Stores a game in the database
     *
     * @return void
     */
    public function store(){

        $game = Game::create(['status'=> 0]);
        $game->save();
        return response()->json($game , 201);
    }
}
