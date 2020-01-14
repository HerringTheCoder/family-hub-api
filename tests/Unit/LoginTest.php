<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

use Carbon\Carbon;
use App\Services\SigninService;

class LoginTest extends TestCase
{
    
    use RefreshDatabase, WithFaker;
      
public function test_login_with_proper_credentials()
{
   $this->withoutExceptionHandling();
   
   \Artisan::call('passport:install'); //creating personal access client
                                        //needed to create token in AuthController line 68
   
   $user = factory(\App\User::class)->create([
     'email' => 'email@example.com',
     'password' => \Hash::make('secret'),
     'active' => 1,
    'deleted_at' => null
     
   ]);                          //creating user for testing
 
   $response = $this->json('POST', '/api/auth/login/', [
    'email' => 'email@example.com',
    'password' => 'secret',
    'active' => 1,
    'deleted_at' => null
   ]);
   
   $response->assertSuccessful();
      $response->assertJson([
         'token_type' => 'Bearer',
         ] //cheking only 'token_type' because it doesn't change, when others do
         //but when we see this it means its ok... trust me
  );
   //dump($response->getContent()); //it shows me, that json returns [$data], which is what we expexted
   
  }

public function test_login_with_wrong_password(){

$this->withoutExceptionHandling();

   \Artisan::call('passport:install'); //creating personal access client
                                        //needed to create token in AuthController line 68
  factory(\App\User::class)->create([
     'email' => 'email@example.com',
     'password' => \Hash::make('secret'),
     'active' => 1,
    'deleted_at' => null
     
   ]);                          //creating user for testing
 
   $response = $this->json('POST', '/api/auth/login/', [
      'email' => 'email@example.com',
      'password' => 'abc',
      'active' => 1,
      'deleted_at' => null
     ]);
                              //login symulation

   $response->assertStatus(401) 
   ->assertJsonFragment([
      'message' => 'Unauthorized'
   ]);
}

public function test_login_with_wrong_email(){

    $this->withoutExceptionHandling();
    
       \Artisan::call('passport:install'); //creating personal access client
                                            //needed to create token in AuthController line 68
    
       factory(\App\User::class)->create([
         'email' => 'email@example.com',
         'password' => \Hash::make('secret'),
         'active' => 1,
        'deleted_at' => null
         
       ]);                          //creating user for testing
    
     
       $response = $this->json('POST', '/api/auth/login/', [
         
        'email' => 'wrong@example.com',
        'password' => 'secret',
        'active' => 1,
        'deleted_at' => null
       ]);                          //login symulation
    
    
       $response->assertStatus(401) 
       ->assertJsonFragment([
         'message' => 'Unauthorized'
       ]);
    
    }

    public function test_login_with_unactivated_account(){

        $this->withoutExceptionHandling();
        
           \Artisan::call('passport:install'); //creating personal access client
                                                //needed to create token in AuthController line 68
        
           factory(\App\User::class)->create([
             'email' => 'email@example.com',
             'password' => \Hash::make('secret'),
             'active' => 0,
            'deleted_at' => null
             
           ]);                          //creating user for testing
        
         
           $response = $this->json('POST', '/api/auth/login/', [
             
            'email' => 'email@example.com',
            'password' => 'secret',
            'active' => 0,
            'deleted_at' => null
           ]);                          //login symulation
        
        
           $response->assertStatus(401)
           ->assertJsonFragment([
            'message' => 'Unauthorized'
          ]);
        
        }

        public function test_user_can_logout()
        {
         \Artisan::call('passport:install'); 

         $user = factory(\App\User::class)->create([
            'email' => 'email@example.com',
            'password' => \Hash::make('secret'),
            'active' => 1,
           'deleted_at' => null
            
          ]);

         $this->be($user); //symulating login

         $token = \Auth::user()->createToken('abc')->accessToken; //creating personal access token
         $headers = ['Authorization' => "Bearer $token"]; //in header we will give the token of user which should logout

          $response = $this->json('GET', 'api/auth/logout', [], $headers)
          ->assertJsonStructure([
            'message'
         ])
          ->assertStatus(200);

        }        
    
}
   