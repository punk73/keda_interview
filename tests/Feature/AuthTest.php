<?php

namespace Tests\Feature;

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
            'email' => 'staff@gmail.com',
            'password' => 'dummydummy',
            'user_type'=> 'Staff'
        ];
        $this->json('POST', '/api/auth/register', $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'token', 'message']);
    }
}
