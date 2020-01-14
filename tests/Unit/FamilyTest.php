<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class FamilyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $prefix;
    protected $user;
    protected $family;
    protected $service;

    public function setUp() :void
    {
        parent::setUp();

        $this->prefix =Str::random(5); //random, because it create new one after every test, and id could be duplicate and there will be an error
        $this->user= factory(\App\User::class)->create([
            'prefix' => $this->prefix,
        ]);

        $this->admin= factory(\App\User::class)->create([
            'prefix' => $this->prefix,
            'type' => 'admin'
        ]);
        $this->service = new \App\Services\SignupActiveService();
        $this->service->active($this->user);
        $this->family = \App\Family::where('founder_id',$this->user->id) -> first();
        
    }

    public function test_admin_can_get_all_families_tables()
    {
        $this->actingAs($this->admin, 'api');

        $response = $this->get('/api/auth/family/all');

          $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
              'data'
          ]);

    }

    public function test_user_cant_get_all_families_tables_if_we_are_not_admin()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->get('/api/auth/family/all');

          $response->assertStatus(401)
          ->assertJsonStructure([
              'message'
          ]);

    }

    public function test_admin_can_edit_and_update_family()
    {
        $this->actingAs($this->admin, 'api');

        $response = $this->call('GET','/api/auth/family/edit',[ //getting family to edit
            'id'=>$this->family->id      
        ]);
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
              'data'
          ]); //getting family to edit

          $response = $this->json('PUT', '/api/auth/family/update', [       //editing family
            'id'=>$this->family->id,
            'name'=>$this->prefix,
            'founder_id'=>$this->user->id,
        ]);
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message'
          ]);

    }

    public function test_edit_if_family_doesnt_exist()
    {
        $this->actingAs($this->admin, 'api');

        $response = $this->call('GET','/api/auth/family/edit',[
            'id'=>''      
        ]);
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message',            //when family doesnt exist
          ]);

    }

    public function test_user_cant_edit_family_if_we_are_not_admin()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->call('GET','/api/auth/family/edit',[ //getting family to edit
            'id'=>$this->family->id      
        ]);
        $response->assertStatus(401)
          ->assertJsonStructure([
              'message'
          ]); 
    }

    public function test_user_cant_update_family_if_we_are_not_admin()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->json('PUT', '/api/auth/family/update', [       //editing family
            'id'=>$this->family->id,
            'name'=>$this->prefix,
            'founder_id'=>$this->user->id,
        ]);
        $response->assertStatus(401)
          ->assertJsonStructure([
              'message'
          ]); 
    }

    public function test_cant_update_when_founder_is_declared_in_other_family()
    {
        $prefix = 'abc';
        $user= factory(\App\User::class)->create([
            'prefix' => $prefix,
        ]);

        $service = new \App\Services\SignupActiveService();
        $service->active($user);

                                                    //I created other family, and we will want to try to
                                                    //attribute this family founder to other family
                                                    //to check if that cant pass

        $this->actingAs($this->admin, 'api');

        $response = $this->json('PUT', '/api/auth/family/update', [       //editing family
            'id'=>$this->family->id,
            'name'=>$this->prefix,
            'founder_id'=>$user->id,
        ]);
        $response->assertStatus(401)
          ->assertJsonStructure([
              'message'
          ]);
    }



}
