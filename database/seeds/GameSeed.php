<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GameSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $game = factory(App\Game::class)->create([
            'status' => 0
        ]);
        factory(App\Army::class)->create([
            "name" => 'Armija 1',
            "units" => 100,
            "game_id" => $game->id,
            "strategy" => 'strongest',
            "order" => Carbon::now()->getTimestamp()
        ]);
        factory(App\Army::class)->create([
            "name" => 'Armija 2',
            "units" => 85,
            "game_id" => $game->id,
            "strategy" => 'weakest',
            "order" => Carbon::now()->addMinutes(1)->getTimestamp()
        ]);
        factory(App\Army::class)->create([
            "name" => 'Armija 3',
            "units" => 90,
            "game_id" => $game->id,
            "strategy" => 'random',
            "order" => Carbon::now()->addMinutes(2)->getTimestamp()
        ]);
        factory(App\Army::class)->create([
            "name" => 'Armija 4',
            "units" => 95,
            "game_id" => $game->id,
            "strategy" => 'strongest',
            "order" => Carbon::now()->addMinutes(3)->getTimestamp()
        ]);
        factory(App\Army::class)->create([
            "name" => 'Armija 5',
            "units" => 100,
            "game_id" => $game->id,
            "strategy" => 'weakest',
            "order" => Carbon::now()->addMinutes(4)->getTimestamp()
        ]);
    }
}
