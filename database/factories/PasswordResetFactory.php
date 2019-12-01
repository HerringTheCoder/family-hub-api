<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PasswordReset;
use Faker\Generator as Faker;

$factory->define(PasswordReset::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'token' => Str::random(20)
        //
    ];
});
