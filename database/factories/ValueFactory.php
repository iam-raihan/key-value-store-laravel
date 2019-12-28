<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Value;
use Faker\Generator as Faker;

$factory->define(Value::class, function (Faker $faker) {
    static $id = 1000;

    return [
        'key' => $id++, // $faker->unique()->randomNumber(4, true)
        'value' => $faker->emoji,
        'expires_at' => now()->addMinutes($faker->biasedNumberBetween(0, 5)),
    ];
});
