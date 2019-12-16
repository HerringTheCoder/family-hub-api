<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Family;
use Faker\Generator as Faker;

$factory->define(Family::class, function (Faker $faker) {
    return [
        'name'=> Str::random(5),
        'founder_id' => rand(1,10)
        //
    ];
});
