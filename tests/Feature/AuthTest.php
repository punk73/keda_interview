<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        \Artisan::call('passport:install');
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        $body = [
            'email' => 'staff@example.com',
            'password' => 'dummydummy',
            'user_type'=> 'Staff'
        ];
        $this->json('POST', '/api/auth/register', $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'token', 'message']);

        $this->assertDatabaseHas('users', [
            'email' => $body['email']
        ]);
    }

    public function test_login(){
        
        $this->seed(DatabaseSeeder::class);

        $body = [
            'email' => 'staff@gmail.com',
            'password' => 'dummydummy',
        ];
        $this->json('POST', '/api/auth/login', $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'token']);
    }
}
