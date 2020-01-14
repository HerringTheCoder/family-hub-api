<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Member;
use App\Family;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Faker\Factory;

use Laravel\Passport\PassportServiceProvider;


class MemberTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $password;
    protected $prefix;
    protected $founderUser;
    protected $user;
    protected $credentials;
    

    public function setUp() :void
    {
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->password = Str::random(10);;
        $this->prefix = Str::random(5);

        $this->founderUser = factory(\App\User::class)->create([
            'prefix' => $this->prefix
           ]);                     
    
    
        $service = new \App\Services\SignupActiveService();
        $service->active($this->founderUser);

        $this->credentials = [
            'email' => $faker->email(),
            'password' => bcrypt($this->password),
            'activation_token' => 'acb',
            'prefix' => $this->prefix,
            'type' => 'default',
        ];

    }

    public function test_founderuser_can_add_member()
    {
        \Notification::fake();

        $this->actingAs($this->founderUser, 'api'); //"login" founderUser
                            //credentials needed for symulating adding member
        
        $response = $this->json('POST', '/api/auth/member/add', factory(\App\Member::class)->make(
            $this->credentials
            
        )->toArray());

        $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
              'data'
          ]);

          $user = \App\User::where('email',$this->credentials['email']) -> first();

        Notification::assertSentTo(
            [\App\User::where('email',$user->email) -> first()],
            \App\Notifications\UserInvite::class);
    }

    public function test_founderuser_can_add_deceased_member()
    {
        $this->actingAs($this->founderUser, 'api'); //"login" founderUser
        
        $response = $this->json('POST', '/api/auth/member/add/deceased', factory(\App\Member::class)->make(
            $this->credentials, [
            'active'=> 1
        ])->toArray());

        $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
              'data'
          ]);

    }

    public function test_edit_member()
    {
        $this->actingAs($this->founderUser, 'api'); //"login" founderUser
                             
        
        $response = $this->json('POST', '/api/auth/member/add', factory(\App\Member::class)->make(
            $this->credentials)->toArray());
        
        /*factory(\App\User::class)->make([
            'prefix'=>$this->prefix,
            'email' => 'email@mail.com',
            'first_name' => 'Anna',
            'last_name' => 'Henr'
        ])->toArray());*/
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
        ]); 
             
        $user = \App\User::where('email',$this->credentials['email']) -> first();

        $this->actingAs($user, 'api');      //"login" user, which will be editing

        $response = $this->get('/api/auth/member/edit');
        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data'
        ]);         
    }

    public function test_update_member()
    {
        $this->actingAs($this->founderUser, 'api'); //"login" founderUser

        $response = $this->json('POST', '/api/auth/member/add', factory(\App\Member::class)->make(
            $this->credentials, [
            'active'=> 1
        ])->toArray());
        $response->assertStatus(201);

        
        $user = \App\User::where('email',$this->credentials['email']) -> first();
        $this->actingAs($user, 'api');

        $response = $this->json('PUT','/api/auth/member/update', [
            'first_name' => 'Ally',
            'last_name' => 'Bundy',
            'day_of_birth' => '1970-10-10',
        ]);
        $response->assertStatus(200)
        ->assertJsonStructure([
            'message'
        ]);        

        $response = $this->assertDatabaseHas($this->prefix.'_members', ['user_id'=>$user['id'], 'first_name'=>'Ally']);

        
    }


    public function test_cant_updated_member_whith_wrong_credentials()
    {
        $this->actingAs($this->founderUser, 'api'); //"login" founderUser
                        
        
        $response = $this->json('POST', '/api/auth/member/add', factory(\App\Member::class)->make(
            $this->credentials
            )->toArray());
        $response->assertStatus(201);
             
        $user = \App\User::where('email',$this->credentials['email']) -> first();

        $this->actingAs($user, 'api'); //here we authenticate us as user, which will be editing his data


        $response = $this->json('PUT','/api/auth/member/update');
        $response->assertStatus(422)
        ->assertJsonFragment([
            "first_name" => ["The first name field is required."],
        ]);
    }
    

    public function test_getting_all_users()
    {
        $this->actingAs($this->founderUser, 'api'); //"login" founderUser                          
        
        $responses = $this->json('POST', '/api/auth/member/add', factory(\App\Member::class)->make(
            $this->credentials
            )->toArray());
             
        $user = \App\User::where('email',$this->credentials['email']) -> first();

        $this->actingAs($user, 'api'); //here we authenticate us as user
        $response = $this->get('/api/auth/member/all');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data'
        ]);
    }

    public function test_getting_info_about_user()
    {
        $user = factory(\App\User::class)->create([
            'prefix' => $this->prefix.'1'         //creating user 
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


        $response = $this->assertDatabaseHas('families', ['name' => $this->prefix])
                          ->assertDatabaseHas($user->prefix.'_members', ['user_id' => $user['id']]);


        

    }

}
