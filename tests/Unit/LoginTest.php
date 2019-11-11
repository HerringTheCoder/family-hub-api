<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class LoginTest extends TestCase
{
    
    use RefreshDatabase, WithFaker;
      

public function test_login_with_proper_credentials()
{
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
    'password' => 'secret',
    'active' => 1,
    'deleted_at' => null
   ]);                          //login symulation

   
   $response->assertSuccessful()
   ->assertJsonStructure([
    'access_token',
    'token_type',
    'expires_at',
        
   ]);           //catching expected json
  
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
    'password' => '',
    'active' => 1,
    'deleted_at' => null
   ]);                          //login symulation


   $response->assertUnauthorized()
   ->assertJsonStructure([
    'message'
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
    
    
       $response->assertUnauthorized()
       ->assertJsonStructure([
        'message'
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
            'password' => '',
            'active' => 0,
            'deleted_at' => null
           ]);                          //login symulation
        
        
           $response->assertUnauthorized()
           ->assertJsonStructure([
            'message'
           ]);
        
        }

        

    

}
   
   


