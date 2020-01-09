<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'active' => 1,
            'activation_token' => "",
            'prefix' => "",
            'type' => "admin",
            'email_verified_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString(),
            'created_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString()
        ]);

        DB::table('users')->insert([
            'email' => 'kowalski@gmail.com',
            'password' => bcrypt('password'),
            'active' => 1,
            'activation_token' => "",
            'prefix' => "family_k",
            'type' => "user",
            'email_verified_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString(),
            'created_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString(),
            'updated_at' => \Carbon\Carbon::createFromDate(2000,01,01)->toDateTimeString()
        ]);
    }
}