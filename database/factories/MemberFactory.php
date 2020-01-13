<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Member;
use Faker\Factory;
use Faker\Generator as Faker;

$factory->define(Member::class, function (Faker $faker) {
    return [
        'user_id' => rand(1,50), 
        'family_id' => rand(1,50),
        'first_name' => $faker->name, 
        'middle_name' => $faker->name, 
        'last_name' =>$faker->name,
        'day_of_birth' => date("Y-m-d"),
        'avatar' =>''
    ];
});
