<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $admin;

    public function setUp() :void
    {
        parent::setUp();


        $this->user= factory(\App\User::class,5)->create();
        $this->user= factory(\App\User::class)->create(
            ['email'=>'lol@lol.pl']
        );

        $this->admin= factory(\App\User::class)->create([
            'type' => 'admin'
        ]);

        $this->user= factory(\App\User::class)->create();
    }

    public function test_admin_can_get_all_users()
    {
        $this->actingAs($this->admin, 'api');

        $response = $this->get('/api/auth/user/all');
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
              'data'
          ]);
    }

    public function test_user_cant_get_all_users()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->get('/api/auth/user/all');
        $response->assertStatus(401)
          ->assertJsonStructure([
              'message',
          ]);
    }

    public function test_admin_can_update_user()
    {
        $this->actingAs($this->admin, 'api');

        $user=\App\User::where('email','lol@lol.pl') -> first();

        $response = $this->json('PUT', '/api/auth/user/update', [       
            'id'=>$user->id,
            'email'=>'abc@mail.com'
        ]);
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message'
          ]);

          $response = $this->assertDatabaseHas('users', ['email' => 'abc@mail.com']);

    }

    public function test_user_cant_update_user()
    {
        $this->actingAs($this->user, 'api');

        $user=\App\User::where('email','lol@lol.pl') -> first();

        $response = $this->json('PUT', '/api/auth/user/update', [     
            'id'=>$user->id,
            'email'=>'abc@mail.com'
        ]);
        $response->assertStatus(401)
          ->assertJsonStructure([
              'message'
          ]);

    }

    public function test_admin_can_activate_user_account()
    {
        $this->actingAs($this->admin, 'api');

        $user=\App\User::where('email','lol@lol.pl') -> first();

        $response = $this->json('POST', '/api/auth/user/active' , [
            'id'=>$user->id,
        ] );
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message'
          ]);

    }

    public function test_admin_can_deactivate_user_account()
    {
        $this->actingAs($this->admin, 'api');

        $user=\App\User::where('email','lol@lol.pl') -> first();

        $response = $this->json('POST', '/api/auth/user/deactive' , [
            'id'=>$user->id,
        ] );
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message'
          ]);
    }



}
