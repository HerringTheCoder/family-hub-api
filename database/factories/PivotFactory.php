<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pivot;
use Faker\Generator as Faker;

$factory->define(Pivot::class, function (Faker $faker) {
    return [
        'user_id' => rand(50,100)
    ];
});
