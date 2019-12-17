<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Army;
use App\Game;
use App\Turn;
use Faker\Generator as Faker;

$factory->define(Turn::class, function (Faker $faker) {
    $damage = rand(1, 50);
    return [
        // "game_id" => factory(Game::class)->create()->id,
        // "attacker_id" => factory(Army::class)->create()->id,
        // "defender_id" => factory(Army::class)->create()->id,
        "damage" => $damage
    ];
});
