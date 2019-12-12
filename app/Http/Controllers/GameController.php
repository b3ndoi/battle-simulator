<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
     * Adds an army to the game
     *
     * @return void
     */
    public function addArmy(Game $game){
        if($game->status == 1){
            return response()->json([
                "message" => "You cannot add armies to a finished game."
            ], 422);
        }
        $attributes = request()->validate([
            "name" => "required",
            "units" => "required|integer|between:80,100",
            "strategy" => [
                'required',
                Rule::in(['random', 'weakest', 'strongest']),
            ],
        ]);
        $game->addArmy($attributes);
        return response()->json($game->armies , 201);
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
