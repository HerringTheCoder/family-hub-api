<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Gallery;
use Faker\Generator as Faker;



$factory->define(Gallery::class, function (Faker $faker) {
    $filepath = storage_path('images');

    if(!File::exists($filepath)){
        File::makeDirectory($filepath); 
    }
    return [
        'author_id' => rand(1,50), 
        'description' => $faker->sentence,
        'photo' => $faker->image($filepath,640,480, null, false),
    ];
});
