<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_fetch_customer()
    {
        $this->seed();

        $user = User::find(2); //staff
        $response = $this
        ->actingAs($user, 'api')
        ->json('GET','/api/customers', [], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure(['success', 'data']);
        
    }
    
    public function test_customer_cannot_fetch_customer()
    {
        $this->seed();

        $user = User::find(1); //staff
        $response = $this
        ->actingAs($user, 'api')
        ->json('GET','/api/customers', [], ['Accept' => 'application/json'])
        ->assertStatus(403)
        ->assertJsonStructure(['success', 'data'])
        ->assertJson(['success' => false]);

    }

    public function test_delete_customer(){
        $this->seed();

        $user = User::find(2); // staff
        $response = $this->actingAs($user, 'api')
            ->json('DELETE', '/api/customers/3', [], ['Accept' => 'application/json']);

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message'])
            ->assertJson(['success' => true]);

        $this->assertSoftDeleted('users', ['id' => 3 ]);
    }




}
