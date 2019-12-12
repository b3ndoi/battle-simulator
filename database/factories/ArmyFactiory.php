<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Army;
use Faker\Generator as Faker;

$factory->define(Army::class, function (Faker $faker) {
    $strategies = ['random', 'weakest', 'strongest'];
    $units = rand(80, 100);
    $strategy = $strategies[rand(0, 2)];
    return [
        "name" => $faker->name(),
        "units" => $units,
        "strategy" => $strategy
    ];
});
