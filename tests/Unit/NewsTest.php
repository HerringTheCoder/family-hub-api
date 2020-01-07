<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $prefix;
    protected $user;
    protected $admin;
    protected $news;
    protected $service;

    public function setUp() :void
    {
        parent::setUp();

        $this->prefix =Str::random(5); //random, because it create new one after every test, and id could be duplicate and there will be an error
        $this->user= factory(\App\User::class)->create([
            'prefix' => $this->prefix
        ]);

        $this->admin= factory(\App\User::class)->create([
            'prefix' => $this->prefix,
            'type' => 'admin'
        ]);


        $this->news = factory(\App\News::class)->create([
            'author_id' => $this->user->id,
        ]);

        $this->news = factory(\App\News::class)->create([
            'author_id' => $this->admin->id,
        ]);

        
        $this->service = new \App\Services\TableService();
        $this->service->addTables($this->prefix);

    }

    public function tearDown() :void {
        parent::tearDown();
        $this->prefix = null;
        $this->user = null;
        $this->news =null;
        $this->service = null;
      }

      public function test_we_can_add_news_and_get_them_all()
    {

        $this->actingAs($this->user, 'api');
        

        $credentials =([
            'title' => 'abc',
            'description' => 'asdsczxfd'
        ]);

        $response = $this->json('POST', '/api/auth/news/add' , $credentials );

        $response->assertStatus(201)
          ->assertJsonStructure([
              'message'
          ]);

          $response = $this->get('/api/auth/news/all');

          $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
              'data'
          ]);

          dump($response->getContent());

          //I combined two methods, add and get all, because I needed filled dabase of news
          //If I would do "get all news" separately, like below, it give me an empty data, and I wanna be sure, that its work
    }


    public function test_get_all_news()
    {
        
        $this->actingAs($this->user, 'api');

        $response = $this->get('/api/auth/news/all');

        $response->assertStatus(201)
        ->assertJsonStructure([
            'message',
            'data'
        ]);
        dump($response->getContent());
    }


    public function test_we_can_edit_and_update_news()
    {

        $this->actingAs($this->admin, 'api');
        

        $credentials =([
            'author_id'=>$this->admin->id,
            'title' => 'abcdfe',
            'description' => 'asdsczxfddh'
        ]);

        $response = $this->json('POST', '/api/auth/news/add' , $credentials );
                                            //so here I created news which will be editing below

        $response->assertStatus(201);

        $response = $this->call('GET','/api/auth/news/edit',[
            'id'=>'1'
        ]);
        $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
              'data'
          ]); //getting news to edit


        $response = $this->json('PUT', '/api/auth/news/update', [
            'id'=>'1',
            'title'=>'otherone',
            'description' => 'somethingelse'
        ]);

        $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
          ]);           //updating news

          
        dump($response->getContent());

        $response = $this->assertDatabaseHas($this->prefix.'_news', ['title' => 'otherone']);
    }


    public function test_delete_news()
    {

        $this->actingAs($this->admin, 'api');
        

        $credentials =([
            'title' => 'abcde',
            'description' => 'asdsczxfd'
        ]);

        $response = $this->json('POST', '/api/auth/news/add' , $credentials );
                                            //so here I created news which will be deleted below


         $response = $this->json('DELETE', '/api/auth/news/delete',[
            'id'=>'1',  //I created new news table, so it allways will be 1
         ]); 
         
         $response->assertStatus(201)
          ->assertJsonStructure([
              'message',
          ]);  
          
        dump($response->getContent());

        $response = $this->assertDatabaseMissing($this->prefix.'_news', ['title' => 'abcde']); 

    }


}
