<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\User;

class SignUpTest extends TestCase
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
          dump($response->getContent()); //showing a message for my use
          $response->assertSuccessful();

         
        $response = $this->assertDatabaseHas('users', ['email' => 'email@example.com'])
                        ->assertDatabaseHas('families', ['name' => 'Kowalscy'])
                        ->assertDatabaseHas('Kowalscy_members', ['user_id' => '1']); 


    }

    //still working on it

}
