<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory;
use App\Services\TableService;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = Faker\Factory::create();
        DB::table('users')->insert([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'active' => 1,
            'activation_token' => "",
            'prefix' => "admin",
            'type' => "admin",
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        $user_id = DB::getPdo()->lastInsertId();
        Log::channel()->notice("User created - id : ".$user_id);

            $service = new TableService();
            $service->addTables('admin');

            factory(App\Family::class)->create([
                'name' => 'admin',
                'founder_id' => $user_id,
            ]);

            $family_id = DB::getPdo()->lastInsertId();
        


        $member = DB::table('admin_members')->insert([
            'user_id' => $user_id,
            'family_id' => $family_id,
            'first_name' => $faker->firstName(),
            'middle_name' => $faker->firstName(),
            'last_name' => $faker->lastName,
            'avatar' => "x",
            'day_of_birth' => $faker->dateTime(),
            'day_of_death' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

    }
}