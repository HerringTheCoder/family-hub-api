<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Services\TableService;

class RelationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $parent;
    protected $parentM;
    protected $partner;
    protected $partner2;
    protected $partnerM;
    protected $partner2M;
    protected $partner3M;
    protected $family;
    protected $relation;

    public function setUp() :void
    {
        parent::setUp();

        $this->prefix =Str::random(5);

        $this->parent = factory(\App\User::class)->create([
            'prefix' => $this->prefix,
        ]);                             //creating parent

        
        $service = new \App\Services\SignupActiveService();
        $service->active($this->parent); //created founder user

        $family = \App\Family::where('founder_id',$this->parent->id) -> first();
    
        $this->partnerM=factory(\App\Member::class)->make([
            'user_id'=>factory(\App\User::class)->create()->id,
            'family_id'=>$family->id
        ]);
        $this->partnerM->setTable($this->parent->prefix.'_members');
        $this->partnerM->save();            //created 1st partner to relation and adding him to table

        
        $this->partner2M=factory(\App\Member::class)->make([
            'user_id'=>factory(\App\User::class)->create()->id,
            'family_id'=>$family->id
        ]);
        $this->partner2M->setTable($this->parent->prefix.'_members');
        $this->partner2M->save();
                                    //created 2nd partner to relation and adding him to table        

                                    $this->partner3M=factory(\App\Member::class)->make([
            'user_id'=>factory(\App\User::class)->create()->id,
            'family_id'=>$family->id
        ]);
        $this->partner3M->setTable($this->parent->prefix.'_members');
        $this->partner3M->save();

    }

    public function test_add_and_get_all_relations()
    {
        $this->actingAs($this->parent, 'api'); //login founder of family

        
        $response = $this->json('POST', '/api/auth/relation/add' , [
            'partner_1_id'=>$this->partnerM->user_id,
            'partner_2_id'=>$this->partner2M->user_id,
        ] );

        $response->assertStatus(201)
          ->assertJsonStructure([
              'message'
          ]);


        $response = $this->get('/api/auth/relation/all');
        $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
              'data'
          ]);

          dump($response->getContent());
    }

/*
    public function test_can_edit_relation()
    {
        $this->actingAs($this->parent, 'api'); //login founder of family

        
        $response = $this->json('POST', '/api/auth/relation/add' , [
            'partner_1_id'=>$this->partnerM->user_id,
            'partner_2_id'=>$this->partner2M->user_id,
        ] );

        $response->assertStatus(201);

       $response = $this->call('GET','/api/auth/relation/edit', [
            'id'=>$this->parent->id
        ]);
        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data'
        ]);

        $response = $this->json('PUT', '/api/auth/relation/update', [       //editing family
            'id'=>$this->parent->id,
            'partner_1_id'=>$this->partnerM->user_id,
            'partner_2_id'=>$this->partner3M->user_id,
            'parent_id'=>$this->parent->id,

        ]);
        $response->assertStatus(201)
          ->assertJsonStructure([
              'message'
          ]);

          dump($response->getContent());
    }
*/
   
/*
public function test_can_delete_relation()
{
    $this->actingAs($this->parent, 'api'); //login founder of family

        
        $response = $this->json('POST', '/api/auth/relation/add' , [
            'partner_1_id'=>$this->partnerM->user_id,
            'partner_2_id'=>$this->partner2M->user_id,
            'parent_id'=>$this->parent->id,
        ] );

        $response = $this->json('DELETE', '/api/auth/relation/delete',[
            'id'=>$this->parent->id, 
         ]); 

         $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
          ]);  
          
        dump($response->getContent());

       }
*/
    
public function test_get_tree()
{
    $this->actingAs($this->parent, 'api'); //login founder of family
    $response = $this->json('POST', '/api/auth/relation/add' , [
        'partner_1_id'=>$this->partnerM->user_id,
        'partner_2_id'=>$this->partner2M->user_id,
    ] );

    $response =$this->call('GET','/api/auth/tree', [
       'id'=>$this->parent->id,
    ]);

    $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
              'data'
          ]);


    dump($response->getContent());

}

public function test_get_single_users()
{
    $this->actingAs($this->parent, 'api'); //login founder of family
    $response = $this->json('POST', '/api/auth/relation/add' , [
        'partner_1_id'=>$this->partnerM->user_id,
    ] );

    $response =$this->call('GET','/api/auth/relation/single', [
        'id'=>$this->parent->id,
     ]);

     $response->assertStatus(200)
          ->assertJsonStructure([
              'message',
              'data'
          ]);


    dump($response->getContent());
}
    

}
