<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\News;
use Faker\Generator as Faker;

$factory->define(News::class, function (Faker $faker) {
    return [
        'author_id' => rand(50,100),
        'title' => Str::random(6),
        'description' => Str::random(),
    ];
});
