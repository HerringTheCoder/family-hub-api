<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Family;
use Faker\Factory;
use Illuminate\Support\Str;

$factory->define(Family::class, function () {
    $faker = Faker\Factory::create();
    return [
        'created_at' => $faker->dateTimeBetween('+0 days', '+0 years'),
        'updated_at' => $faker->dateTimeBetween('+0 days', '+0 years')
    ];
});
