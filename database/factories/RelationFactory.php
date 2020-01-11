<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Relation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Relation::class, function (Faker $faker) {
    return [
        'parent_id'=> rand(3,8),
        'partner_id'=> rand(8,12),
    ];
});
