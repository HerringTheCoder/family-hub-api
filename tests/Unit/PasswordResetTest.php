<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\User;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    

    public function test_user_can_get_reset_password_link()
    {
        $this->withoutExceptionHandling();
        Notification::fake();

        $user = factory(\App\User::class)->create([
            'email' => 'email@example.com',
        ]);

        $response = $this->json('POST', '/api/password/create', [
            'email' => 'email@example.com'
        ])
        ->assertJsonStructure([
            'message'
        ])
        ->assertSuccessful();

        Notification::assertSentTo(
            [$user],
            \App\Notifications\PasswordResetRequest::class);
    }


    public function test_user_cant_get_reset_password_link_if_he_does_not_have_account_already()
        {
        $this->withoutExceptionHandling();
        Notification::fake();

        $user = factory(\App\User::class)->create([
            'email' => 'email@example.com',
        ]);

        $response = $this->json('POST', '/api/password/create', [
            'email' => 'mail@example.com'
        ])
        ->assertJsonStructure([
            'message'
        ])
        ->assertStatus(404);

        Notification::assertNotSentTo(
            [$user],
            \App\Notifications\PasswordResetRequest::class);
        }

    

    public function test_token_find()
    {
        $this->withoutExceptionHandling();

        $passwordReset = factory(\App\PasswordReset::class)->create([
            'token'=>'abc'
        ]);

        $response = $this->call('GET', "/api/password/find/abc")
        ->assertJsonStructure([
            'email',
            'token'
        ]); //In reference to 'return response()->json($passwordReset);'

    }

    public function test_token_find_if_its_not_proper()
    {
        $this->withoutExceptionHandling();

        $passwordReset = factory(\App\PasswordReset::class)->create([
            'token'=>'abc'
        ]);

        $response = $this->call('GET', "/api/password/find/abcd")
        ->assertJsonStructure([
            'message'
        ])
        ->assertStatus(404);

    }

    public function test_user_can_reset_his_password()
    {
        Notification::fake();
        $this->withoutExceptionHandling();

        $user = factory(\App\User::class)->create([
            'email' => 'email@email.pl',
        ]);

        $passwordReset = factory(\App\PasswordReset::class)->create([
            'email' => 'email@email.pl',
            'token'=>'abc'
        ]);

        $response=$this->json('POST', '/api/password/reset', [
            'token' => 'abc',
            'email'=> 'email@email.pl',
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword',
        ]);

        $user = $user->fresh();
        $this->assertTrue(\Hash::check('newPassword', $user->password));
        $response->assertSuccessful()
        ->assertJsonStructure([
            'email',
        ]);
        

        Notification::assertSentTo(
            [$user],
            \App\Notifications\PasswordResetSuccess::class);

    }

    public function test_user_cant_reset_his_password_with_wrong_email()
    {
        Notification::fake();
        $this->withoutExceptionHandling();
        
        $passwordReset = factory(\App\PasswordReset::class)->create([
            'email' => 'email@email.pl',
            'token'=>'abc'
        ]);

        $response=$this->json('POST', '/api/password/reset', [
            'token' => 'abc',
            'email'=> 'email@email.pl',
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword',
        ]);

        $response->assertStatus(404)
        ->assertJsonStructure([
            'message',
        ]);

    }

    public function test_user_cant_reset_his_password_with_wrong_password_reset_token()
    {
        Notification::fake();
        $this->withoutExceptionHandling();

        $user = factory(\App\User::class)->create([
            'email' => 'email@email.pl',
        ]);

        $passwordReset = factory(\App\PasswordReset::class)->create([
            'email' => 'email@email.pl',
            'token'=>'abc'
        ]);

        $response=$this->json('POST', '/api/password/reset', [
            'token' => 'abcf',
            'email'=> 'email@email.pl',
            'password' => 'newPassword',
            'password_confirmation' => 'newPassword',
        ]);

        $user = $user->fresh();
        $this->assertFalse(\Hash::check('newPassword', $user->password));
        $response->assertStatus(404)
        ->assertJsonStructure([
            'message',
        ]);
        

    }


}

