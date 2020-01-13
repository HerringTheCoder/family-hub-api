<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Factory;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(User::class, function () {
    $faker = Faker\Factory::create();
    return [
        'email' => $faker->email(),
        'password' => bcrypt('password'),
        'active' => 1,
        'activation_token' => "",
        'type' => "user",
        'email_verified_at' => $faker->dateTime(),
        'created_at' => $faker->dateTime(),
        'updated_at' => $faker->dateTime(),
        'prefix' => Str::random(10),
    ];
});
