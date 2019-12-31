<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Member;
use App\Family;
use Illuminate\Support\Facades\Notification;

use Laravel\Passport\PassportServiceProvider;


class MemberTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    


    public function test_founderuser_can_add_member()
    {
        \Notification::fake();

        $password = 'pashfhghfs';
        $prefix = 'Kolwaski';

       $founderUser = factory(\App\User::class)->create([
        'prefix' => $prefix
       ]);                      //creating founderUser, which will be adding a family member

        
        factory(\App\Family::class)->create([
           'founder_id' => $founderUser->id
        ]);                     //creating family, we need to symulate registration that give us needed information


        $this->actingAs($founderUser, 'api'); //"login" founderUser

        
        $service = new \App\Services\TableService();
        $service->addTables('Kolwaski'); //we need to have a table, which is normally creating while registration
                                        //so I creaded it here


        $credentials =[
            'first_name' => 'Kolwaski',
            'email' => 'email@email.com',
            'password' => bcrypt($password),
            'activation_token' => 'acb',
            'prefix' => $prefix,
            'type' => 'default',
        ];                              //credentials needed for symulating adding member
        
        $response = $this->json('POST', '/api/auth/member/add', $credentials);
        $response->assertStatus(201)
          ->assertJsonStructure([
              'message'
          ]);
          dump($response->getContent());

        Notification::assertSentTo(
            [\App\User::where('email','email@email.com') -> first()],
            \App\Notifications\UserInvite::class);
    }


    public function test_founderuser_can_add_deceased_member()
    {

        $password = 'pashfhghfs';
        $prefix = 'Nowak';

       $founderUser = factory(\App\User::class)->create([
        'prefix' => $prefix
       ]);                      //creating founderUser, which will be adding a family member

        
        factory(\App\Family::class)->create([
           'founder_id' => $founderUser->id
        ]);                     //creating family, we need to symulate registration that give us needed information


        $this->actingAs($founderUser, 'api'); //"login" founderUser

        
        $service = new \App\Services\TableService();
        $service->addTables('Nowak'); //we need to have a table, which is normally creating while registration
                                        //so I creaded it here


        $credentials =[
            'email' => 'email@email.com',
            'password' => bcrypt($password),
            'activation_token' => "",
            'active'=> 1,
            'prefix' => $prefix,
            'type' => 'default',
            'first_name' => 'Ewa',
            'middle_name' => 'Karolina',
            'last_name' => 'Nowak',
            'day_of_birth' => '1970-10-10',
            'day_of_death' => '2018-10-10'
        ];                              //credentials needed for symulating adding member
        
        $response = $this->json('POST', '/api/auth/member/add/deceased', $credentials);
        $response->assertStatus(201)
          ->assertJsonStructure([
              'message'
          ]);
          dump($response->getContent());

    }

    public function test_edit_member()
    {
        $prefix= 'Adamowicz';

        $user = factory(\App\User::class)->create([
            'prefix' => $prefix
        ]);
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix
        ]);
        $family = factory(\App\Family::class)->create([
            'founder_id' => $founderUser->id
        ]);
        $member = factory(\App\Member::class)->create([
            'user_id'=> $user->id,
            'family_id'=>$family->id,
            'day_of_birth' => '1970-10-10',
            'avatar'=> ''
        ]);

                
        $service = new \App\Services\TableService();
        $service->addTables('Adamowicz');
        

        $this->actingAs($founderUser, 'api'); //"login" founderUser


        $response = $this->get('/api/auth/member/edit');
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data'
        ]);
        
        dump($response->getContent());
    }

    public function test_update_member()
    {

        $prefix= 'Wikidajlo';
        $user = factory(\App\User::class)->create([
            'prefix' => $prefix         //creating user 
        ]);
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix             //creating founder of family
        ]);
        $family = factory(\App\Family::class)->create([
            'founder_id' => $founderUser->id        //creating family
        ]);
        $member = factory(\App\Member::class)->create([
            'user_id'=> $user->id,
            'family_id'=>$family->id,
            'avatar' => ''
            ]);                     //creating member to update

               
        $service = new \App\Services\TableService();
        $service->addTables('Wikidajlo');  //creating table of this family


        $this->actingAs($founderUser, 'api'); //"login" founderUser


        $response = $this->json('PUT','/api/auth/member/update', [
            'first_name' => 'Ally',
            'last_name' => 'Bundy',
            'day_of_birth' => '1970-10-10',
        ]);
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message'
        ]);        
        dump($response->getContent());
    }

    public function test_updated_member()
    {
        
        $prefix= 'Szczesny';
        $user = factory(\App\User::class)->create([
            'prefix' => $prefix         //creating user 
        ]);
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix             //creating founder of family
        ]);
        $family = factory(\App\Family::class)->create([
            'founder_id' => $founderUser->id        //creating family
        ]);
        $member = factory(\App\Member::class)->create([
            'user_id'=> $user->id,
            'family_id'=>$family->id,
            'avatar' => ''
            ]);                     //creating member to update

               
        $service = new \App\Services\TableService();
        $service->addTables('Szczesny');  //creating table of this family


        $this->actingAs($founderUser, 'api'); //"login" founderUser


        $response = $this->json('PUT','/api/auth/member/update', [
            'first_name' => 'Ally',
            'last_name' => 'Bundy',
            'day_of_birth' => '1970-10-10',
        ]);
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message'
        ]);
        dump($response->getContent());
    }

    public function test_cant_updated_member_whith_wrong_credentials()
    {
        
        $prefix= 'Lewandowski';
        $user = factory(\App\User::class)->create([
            'prefix' => $prefix         //creating user 
        ]);
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix             //creating founder of family
        ]);
        $family = factory(\App\Family::class)->create([
            'founder_id' => $founderUser->id        //creating family
        ]);
        $member = factory(\App\Member::class)->create([
            'user_id'=> $user->id,
            'family_id'=>$family->id,
            'avatar' => ''
            ]);                     //creating member to update

               
        $service = new \App\Services\TableService();
        $service->addTables('Lewandowski');  //creating table of this family


        $this->actingAs($founderUser, 'api'); //"login" founderUser


        $response = $this->json('PUT','/api/auth/member/update');
        $response->assertStatus(422)
        ->assertJsonFragment([
            "first_name" => ["First name is required!"],
            "last_name"=> ["Last name is required!"],
            "day_of_birth" =>["Day of birth is required!"]
        ]);
        dump($response->getContent());
    }
    

    public function test_getting_all_users()
    {
        $prefix= 'Wokulski';
        $user = factory(\App\User::class)->create([
            'prefix' => $prefix         //creating user 
        ]);
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix             //creating founder of family
        ]);
        $family = factory(\App\Family::class)->create([
            'founder_id' => $founderUser->id       //creating family
        ]);
        $member = factory(\App\Member::class)->create([
            'user_id'=> $user->id,
            'family_id'=>$family->id,
            'avatar' => ''
            ]);                     //creating member to update

               
        $service = new \App\Services\TableService();
        $service->addTables($prefix);  //creating table of this family


        $this->actingAs($founderUser, 'api');

        $response = $this->get('/api/auth/member/all');

        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data'
        ]);
        dump($response->getContent());
    }


}
