<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Services\TableService;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(FamiliesTableSeeder::class);
        

    }

    
}
