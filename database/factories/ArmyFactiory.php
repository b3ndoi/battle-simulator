<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Army;
use App\Game;
use Faker\Generator as Faker;
use Carbon\Carbon;
$factory->define(Army::class, function (Faker $faker) {
    $strategies = ['random', 'weakest', 'strongest'];
    $units = rand(80, 100);
    $strategy = $strategies[rand(0, 2)];
    return [
        // Remove after testing
        // "id" => $faker->uuid(),
        // "game_id" => factory(Game::class)->create()->id,
        "name" => $faker->word(),
        "units" => $units,
        "strategy" => $strategy,
        "order" => Carbon::now()->getTimestamp()
    ];
});
