<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
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

$factory->define(User::class, function (Faker $faker) {
    return [
        //'name' => $faker->name,   //because of the error that the column 'name' not found
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'activation_token' => Str::random(80),
        /*
        added 'activation_token' because of the error:
Field 'activation_token' doesn't have a default value (SQL: insert into `users` (`email`, `email_verified_at`, `password`, `remember_token`, `active`, `deleted_at`, `updated_at`, `created_at`)
        */
        'prefix' => $faker->name,
    ];
});
