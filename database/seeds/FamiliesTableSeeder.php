<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Services\TableService;
use Carbon\Carbon;

class FamiliesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $prefix = $faker->shuffle('xyzqwert');
        for ($i=0; $i < 20; $i++) { 

            factory(App\User::class)->create([
                'prefix' => $prefix,
            ]);

            $user_id = DB::getPdo()->lastInsertId();
            Log::channel()->notice("User created - id : ".$user_id);

            if($i == 0){
                $service = new TableService();
                $service->addTables($prefix);

                factory(App\Family::class)->create([
                    'name' => $prefix,
                    'founder_id' => $user_id,
                ]);

                $family_id = DB::getPdo()->lastInsertId();
            }


            $member = DB::table($prefix.'_members')->insert([
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

            
            for ($z=0; $z < 3; $z++) { 
                DB::table($prefix.'_news')->insert([
                    'author_id' => $user_id,
                    'title' => $faker->word,
                    'description' => $faker->text($maxNbChars = 200),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
                
                DB::table($prefix.'_gallery')->insert([
                    'author_id' => $user_id,
                    'description' => $faker->text($maxNbChars = 200),
                    'filename' => "x",
                    'original_filename' => "x",
                    'mime' => "x",
                    'filename' => "x",
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }



            Log::channel()->notice("User created - id : ".$user_id." and member in family ".$prefix);
            
        }

        
        $member = DB::table($prefix.'_members')->get();

        for ($i=0; $i < 20; $i++) { 
                if($i == 0){
                    DB::table($prefix.'_relations')->insert([
                        'partner_1_id' => $member[$i]->user_id,
                        'partner_2_id' => $member[$i+1]->user_id,
                        'parent_id' => null,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                    
                    $relation = DB::getPdo()->lastInsertId();
                    $i += 2;
                
                    DB::table($prefix.'_relations')->insert([
                        'partner_1_id' => $member[$i]->user_id,
                        'partner_2_id' => $member[$i+1]->user_id,
                        'parent_id' => $relation,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                    $relation_3 = DB::getPdo()->lastInsertId();
                    $i += 2;

                    DB::table($prefix.'_relations')->insert([
                        'partner_1_id' => $member[$i]->user_id,
                        'partner_2_id' => $member[$i+1]->user_id,
                        'parent_id' => $relation,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                    $i ++;
                    $relation_2 = DB::getPdo()->lastInsertId();
                    continue;
                }


                
                if(($i % 2 == 0) && $i >= 7){
                    $relation = DB::table($prefix.'_relations')->insert([
                        'partner_1_id' => $member[$i]->user_id,
                        'partner_2_id' => $member[$i+1]->user_id,
                        'parent_id' => $relation_2,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ]);
                        $i++;
                        continue;
                }

                if(($i % 2 == 1) && $i >= 7){
                    $relation = DB::table($prefix.'_relations')->insert([
                        'partner_1_id' => $member[$i+1]->user_id,
                        'partner_2_id' => null,
                        'parent_id' => $relation_3,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                    continue;
                }
        }

    }

    
}
