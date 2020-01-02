<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\User;

class SignupTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function test_registration_create_user_family_and_member()
    {
        
       Notification::fake();
        
        $credentials = [
            'email' => 'email@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'activation_token' => 'dsfdsgfdsfsfsdfsd',
            'prefix' => 'Lol',      //above - user credentials
            'name' => 'Kowalscy' //name of family

          ];


          $response = $this->json('POST', '/api/auth/signup', $credentials );
          //dump($response->getContent()); //showing a message for my use
          $response->assertSuccessful()
                    ->assertStatus(201)
                    ->assertJsonStructure([
                          'message'
                      ]);

          dump($response->getContent());

        $response = $this->assertDatabaseHas('users', ['email' => 'email@example.com']);          
        
        Notification::assertSentTo(\App\User::where('email','email@example.com') -> first(), \App\Notifications\SignupActivate::class);      
                
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
        dump($response->getContent());

    }


      public function test_user_cant_signup_with_email_taken()
      {
        factory(User::class)->create([
          'email' => 'emails@example.com',
        ]);
        
        $credentials = [
          'email' => 'emails@example.com',
          'password' => 'secret',
          'password_confirmation' => 'secret',
          'activation_token' => 'dsfdsgfdsfsfsdfsd',
          'prefix' => 'Lol',      
          'name' => 'Kowalscy'
          
        ];

        $response = $this->json('POST', '/api/auth/signup', $credentials)
          ->assertJsonFragment([
            'email' => ['The email has already been taken.']
          ])
          ->assertStatus(422);
          dump($response->getContent());
          
      }

      public function test_user_can_activate_his_account_via_email_with_proper_token()
      {
        $user = factory(User::class)->create([
          'email' =>'maill@example.com',
          'prefix' => 'Kosty',
          'activation_token' => 'abc',
        ]);

        $response = $this->call('GET', "/api/auth/signup/activate/abc")
              ->assertStatus(201)
              ->assertJsonStructure([
                  'message',
                  'data'
              ]);
              
                
        dump($response->getContent());

        //$user = \App\User::where('email','maill@example.com') -> first();

        $response = $this->assertDatabaseHas('families', ['name' => 'Kosty'])
                          ->assertDatabaseHas('Kosty_members', ['user_id' => $user['id']]);    
        
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
                
              dump($response->getContent());

              
      }

      public function test_member_can_activate_his_account()
      {
        $user=factory(User::class)->create([
          'activation_token' => 'abc',
        ]);



        $response = $this->call('GET', "/api/auth/signup/activate/member/abc")
              ->assertStatus(201)
              ->assertJsonStructure([
                'email',
                'message',
                'token'
              ]);
              dump($response->getContent());
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
              dump($response->getContent());
      }
}