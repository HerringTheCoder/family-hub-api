<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PivotTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_get_pivot()
    {
        $prefix = 'Rakowski';

        $user = factory(\App\User::class)->create([
            'prefix' => $prefix             //creating founder of family
        ]);

        $pivot = factory(\App\Pivot::class)->create([
            'user_id'=>$user->id
        ]);

        $service = new \App\Services\TableService();
        $service->addTables($prefix);  //creating table pivot


        $this->actingAs($user, 'api');

        $response = $this->get('/api/auth/pivot/get');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'count'
        ]);
        dump($response->getContent());

    }
}
