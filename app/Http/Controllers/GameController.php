<?php

namespace App\Http\Controllers;

use App\Game;
use App\Library\BattleSimulation;
use Carbon\Carbon;
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
        $games = Game::with('armies')->get();
        return response()->json($games , 200);
    }
    /**
     * Lists game armies
     *
     * @return void
     */
    public function armies(Game $game){
        return response()->json($game->armies , 200);
    }
    /**
     * Adds an army to the game
     *
     * @param Game $game
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
        $attributes['order'] = Carbon::now()->getTimestamp();
        
        if($game->turns->count() > 0){
            $attributes['order'] = -1 * $attributes['order'];
        }
        $game->addArmy($attributes);
        return response()->json($game->armies , 201);
    }
    /**
     * Starts the game or runs am Attack
     *
     * @param [int] $game_id
     * @return void
     */
    public function runAttack($game_id){
        $game = Game::where('id', '=', $game_id)->with(['armies', 'turns'])->get()->first();
        if($game->armies->count() < 5 && $game->turns->count() == 0){
            return response()->json([
                "message" => "You cannot start a game with less then 5 armies."
            ], 422);
        }
        if($game->status == 1){
            return response()->json([
                "message" => "Game finished."
            ], 200);
        }
        $armies = $game->armies->where("units", ">", 0);
        $battle = new BattleSimulation($armies);
        $logs = $battle->getTurnLogs();
        if($battle->getArmies()->count() == 1){
            $game->status = 1;
            $logs[] = "Game Finished!";
            $game->save();
            return response()->json($logs, 200);
        }
        $logs[] = "Waiting for next turn...";
        return response()->json($logs, 200);

    }

    public function getLogs($game_id){
        $game = Game::where('id', '=', $game_id)->with(['turns.attacker', 'turns.defender'])->get()->first();
        if($game->turns->count() == 0){
            return response()->json(["Game not started"]);
        }
        
        $logs[] = "Game started";
        foreach($game->turns as $turn){
            $dateTime = "<span class='text-yellow-400'>[".Carbon::parse($turn->created_at)->format('d.m.Y. H:i:s')."]</span>  ";
            if($turn->damage > 0){
                if($turn->is_destroied == 1){
                    $logs[] = $dateTime.$turn->attacker->name." ARMY 🚀 attacked ".$turn->defender->name." ARMY and DESTROYED IT 🏴‍☠️";
                }else{
                    $logs[] = $dateTime.$turn->attacker->name." ARMY 🚀 attacked ".$turn->defender->name." ARMY and DESTROYED ".$turn->damage." UNITS 💥";
                }
            }else{
                $logs[] = $dateTime.$turn->attacker->name." ARMY 🚀 attacked ".$turn->defender->name." ARMY and MISSED 🙊";
            }
        }
        $armies = $game->armies->where('units', '>', 0);
        if($armies->count() == 1){
            $logs[] = "🏆 ".$armies->first()->name." WON THE GAME 🏆";
        }

        if($game->status == 1){
            $logs[] = "Game Finished!";
        }else{
            $logs[] = "Waiting for next turn...";
        }

        return response()->json($logs);
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
    /**
     * Resets a specific game
     *
     * @param Game $game
     * @return void
     */
    public function resetGame(Game $game){
        if($game->turns->count() == 0){
            return response()->json([
                "message" => "You cannot restart a game that has not started."
            ], 422);
        }
        $armies = $game->armies;

        foreach($armies as $army){
            $army->resetDamageTaken();
        }
        $game->status = 0;
        $game->save();
        return response($game->armies, 200);

    }
}
