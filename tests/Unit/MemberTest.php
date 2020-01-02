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

        
        $service = new \App\Services\SignupActiveService();
        $service->active($founderUser);         //creating family and adding user to the family

        $this->actingAs($founderUser, 'api'); //"login" founderUser

        $credentials =[
            'first_name' => 'Anna',
            'email' => 'email@email.com',
            'password' => bcrypt($password),
            'activation_token' => 'acb',
            'prefix' => $prefix,
            'type' => 'default',
        ];                              //credentials needed for symulating adding member
        
        $response = $this->json('POST', '/api/auth/member/add', $credentials);
        $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
              'relation'
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

        
        $service = new \App\Services\SignupActiveService();
        $service->active($founderUser);

        $this->actingAs($founderUser, 'api'); //"login" founderUser

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
              'message',
              'relation'
          ]);
          dump($response->getContent());

    }

    public function test_edit_member()
    {
        $prefix= 'Adamowicz';

        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix
        ]);

        $service = new \App\Services\SignupActiveService();
        $service->active($founderUser);

        $this->actingAs($founderUser, 'api'); //"login" founderUser

        $credentials =[
            'first_name' => 'Anna',
            'email' => 'email@email.com',
            'password' => bcrypt('lol'),
            'activation_token' => 'acb',
            'prefix' => $prefix,
            'type' => 'default',
        ];                              
        
        $responses = $this->json('POST', '/api/auth/member/add', $credentials);

        //we needed user in family, so Im symulating adding one to the family
             
        $user = \App\User::where('email','email@email.com') -> first();

        $this->actingAs($user, 'api');      //"login" user, which will be editing

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

        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix
        ]);

        $service = new \App\Services\SignupActiveService();
        $service->active($founderUser);

        $this->actingAs($founderUser, 'api'); //"login" founderUser

        $credentials =[
            'first_name' => 'Anna',
            'email' => 'mail@email.com',
            'password' => bcrypt('lol'),
            'activation_token' => 'acb',
            'prefix' => $prefix,
            'type' => 'default',
        ];                             
        
        $responses = $this->json('POST', '/api/auth/member/add', $credentials);

        //above we created member to edit

        
        $user = \App\User::where('email','mail@email.com') -> first();
        $this->actingAs($user, 'api');
       
         //here we authenticate us as user, which will be editing his data


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

        $response = $this->assertDatabaseHas($prefix.'_members', ['user_id'=>$user['id'], 'first_name'=>'Ally']);

        
    }


    public function test_cant_updated_member_whith_wrong_credentials()
    {
        
        $prefix= 'Lewandowski';
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix
        ]);

        $service = new \App\Services\SignupActiveService();
        $service->active($founderUser);

        $this->actingAs($founderUser, 'api'); //"login" founderUser

        $credentials =[
            'first_name' => 'Anna',
            'email' => 'email@email.com',
            'password' => bcrypt('lol'),
            'activation_token' => 'acb',
            'prefix' => $prefix,
            'type' => 'default',
        ];                              
        
        $responses = $this->json('POST', '/api/auth/member/add', $credentials);

        //above we created member to edit
             
        $user = \App\User::where('email','email@email.com') -> first();

        $this->actingAs($user, 'api'); //here we authenticate us as user, which will be editing his data


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
        $founderUser = factory(\App\User::class)->create([
            'prefix' => $prefix
        ]);

        $service = new \App\Services\SignupActiveService();
        $service->active($founderUser);

        $this->actingAs($founderUser, 'api'); //"login" founderUser

        $credentials =[
            'first_name' => 'Anna',
            'email' => 'email@email.com',
            'password' => bcrypt('lol'),
            'activation_token' => 'acb',
            'prefix' => $prefix,
            'type' => 'default',
        ];                              
        
        $responses = $this->json('POST', '/api/auth/member/add', $credentials);

             
        $user = \App\User::where('email','email@email.com') -> first();

        $this->actingAs($user, 'api'); //here we authenticate us as user
        

        $response = $this->get('/api/auth/member/all');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data'
        ]);
        dump($response->getContent());
    }

    public function test_getting_info_about_user()
    {
        $prefix= 'Sobieski';


        $user = factory(\App\User::class)->create([
            'prefix' => $prefix         //creating user 
        ]);

        $service = new \App\Services\SignupActiveService();
        $service->active($user);

        $this->actingAs($user, 'api');
        

        $response = $this->get('/api/auth/member/info');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data'
        ]);

        dump($response->getContent());

        $response = $this->assertDatabaseHas('families', ['name' => $prefix])
                          ->assertDatabaseHas($prefix.'_members', ['user_id' => $user['id']]);


        

    }

}
