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
          dump($response->getContent());
    }

    public function test_admin_can_update_user()
    {
        $this->actingAs($this->admin, 'api');

        $user=\App\User::where('email','lol@lol.pl') -> first();

        $response = $this->json('PUT', '/api/auth/user/update', [       //editing family
            'id'=>$user->id,
            'email'=>'abc@mail.com'
        ]);
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message'
          ]);
          dump($response->getContent());

          $response = $this->assertDatabaseHas('users', ['email' => 'abc@mail.com']);

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
          dump($response->getContent());

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
          dump($response->getContent());
    }



}
