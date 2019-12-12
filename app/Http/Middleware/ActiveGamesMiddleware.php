<?php

namespace App\Http\Middleware;

use App\Game;
use Closure;

class ActiveGamesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $active_game_count = Game::isFinished(0)->count();
        if($active_game_count >= 5){
            return response()->json([
                "message" => "There can be only 5 games active"
            ], 422);
        }
        return $next($request);
    }
}
