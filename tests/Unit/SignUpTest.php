<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\User;
use Faker\Factory;

class SignupTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $credentials;

    public function setUp() :void
    {
        parent::setUp();

        $faker = \Faker\Factory::create();

        $this->credentials = [
          'email' => $faker->email(),
          'password' => 'secret',
          'password_confirmation' => 'secret',
          'activation_token' => 'dsfdsgfdsfsfsdfsd',
          'prefix' => $faker->name(),
          'name' => $faker->name(),

        ];

    }
    
    public function test_registration_create_user_family_and_member()
    {
        
       Notification::fake();
        
          $response = $this->json('POST', '/api/auth/signup', $this->credentials );
          //dump($response->getContent()); //showing a message for my use
          $response->assertStatus(201)
                    ->assertJsonStructure([
                          'message'
                      ]);


        $user = \App\User::where('email',$this->credentials['email']) -> first();

        $response = $this->assertDatabaseHas('users', ['email' => $user['email']]);          
        
        Notification::assertSentTo(\App\User::where('email' , $user['email']) -> first(), \App\Notifications\SignupActivate::class);      
                
    }
  

    

    public function test_user_cannot_register_with_incorrect_credentials()
    {
      $credentials = [
        'email' => null,
        'password' => null,
        'password_confirmation' => null,
        'name' => null
    ];

      $response = $this->json('POST', '/api/auth/signup', $credentials)
        ->assertJsonFragment([
          'email' => ['Email is required!'],
          'password' => ['Password is required!'],
          'name' => ['Name of family is required!']
        ])
        ->assertStatus(422);

    }


      public function test_user_cant_signup_with_email_taken()
      {
        factory(User::class)->create([
          'email' => $this->credentials['email'],
        ]);
        

        $response = $this->json('POST', '/api/auth/signup', $this->credentials)
          ->assertJsonFragment([
            'email' => ['The email has already been taken.']
          ])
          ->assertStatus(422);
          
      }

      public function test_user_can_activate_his_account_via_email_with_proper_token()
      {
        $user = factory(User::class)->create([
          'activation_token' => 'abc',
        ]);

        $response = $this->call('GET', "/api/auth/signup/activate/abc")
              ->assertStatus(200)
              ->assertJsonStructure([
                  'message',
                  'data'
              ]);
              

        //$user = \App\User::where('email','maill@example.com') -> first();

        $response = $this->assertDatabaseHas('families', ['name' => $user['prefix']])
                          ->assertDatabaseHas($user->prefix.'_members', ['user_id' => $user['id']]);    
        
      }

      public function test_user_cant_activate_his_account_via_email_with_wrong_token()
      {
        factory(User::class)->create([
          'activation_token' => 'abc',
        ]);

        $response = $this->call('GET', "/api/auth/signup/activate/abcd")
              ->assertStatus(404)
              ->assertJsonStructure([
                  'message'
              ]);
                

              
      }

      public function test_member_can_activate_his_account()
      {
        $user=factory(User::class)->create([
          'activation_token' => 'abc',
        ]);



        $response = $this->call('GET', "/api/auth/signup/activate/member/abc")
              ->assertStatus(200)
              ->assertJsonStructure([
                'email',
                'message',
                'token'
              ]);
      }


      public function test_member_cant_activate_his_account_with_wrong_token()
      {
        $user=factory(User::class)->create([
          'activation_token' => 'abc',
        ]);



        $response = $this->call('GET', "/api/auth/signup/activate/member/abce")
              ->assertStatus(404)
              ->assertJsonStructure([
                'message',
              ]);
      }
}