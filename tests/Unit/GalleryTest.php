<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Gallery;
use App\User;
use App\Member;
use App\Family;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class GalleryTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    protected $prefix;
    protected $user;
    protected $service;
    protected $photo;

    public function setUp() :void
    {
        parent::setUp();

        $this->prefix =Str::random(5); //random, because it create new one after every test, and id could be duplicate and there will be an error
        $this->user= factory(\App\User::class)->create([
            'prefix' => $this->prefix
        ]);

        $this->service = new \App\Services\TableService();
        $this->service->addTables($this->prefix);

        $this->photo = factory(\App\Gallery::class)->make([
            'author_id' => $this->user->id,
            'photo_input' =>  \Illuminate\Http\UploadedFile::fake()->create('test.png', $kilobytes = 0)]);

    }

    public function test_we_can_add_and_get_all()
    {
    
        $this->actingAs($this->user, 'api');

        $response= $this->json('POST', '/api/auth/gallery/add', $this->photo->toArray());
        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
        ]);


        $response = $this->get('/api/auth/gallery/all');

        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data'
        ]);
        dump($response->getContent());

        $response = $this->assertDatabaseHas($this->prefix.'_gallery', ['author_id' => $this->user->id]);

    }

    public function test_delete_gallery()
    {
        
        $this->actingAs($this->user, 'api');

        $response= $this->json('POST', '/api/auth/gallery/add', $this->photo->toArray());
        $response->assertStatus(201);       //creating gallery to delete

        $response = $this->json('DELETE', '/api/auth/gallery/delete',[
            'id'=>'1',  //I created new table, so it allways will be 1
         ]); 
         
         $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
          ]);  
          
        dump($response->getContent());

        $response = $this->assertDatabaseMissing($this->prefix.'_gallery', ['description' => $this->photo->description]); 

    }


}
