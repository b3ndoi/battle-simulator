<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Turn;
use Faker\Generator as Faker;

$factory->define(Turn::class, function (Faker $faker) {
    $damage = rand(1, 50);
    return [
        "damage" => $damage
    ];
});
