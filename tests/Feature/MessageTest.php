<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Builder\Function_;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('passport:install');
    }

    public function test_send_message() {
        $this->seed();

        $user = User::find(1);
        $body = [
            'recipient_id' => 2,
            'content' => "halo min!"
        ];
        $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/messages', $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'message', 'data'])
        ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', $body);
    }

    public function test_send_message_from_staff_to_customer()
    {
        $this->seed();

        $user = User::find(2); //staff
        $body = [
            'recipient_id' => 1,
            'content' => "halo customer!"
        ];
        $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/messages', $body, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data'])
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', $body);
    }

    public function test_get_message_as_customer(){
        $this->seed();

        $user = User::find(1); //customer
        
        $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/messages', [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data'])
            ->assertJson(['success' => true])
            ->assertJson(['data' => []])
            ->assertJsonCount(2, 'data.*'); //it's from seeder, dua percakapan

    }

    public function test_get_message_as_staff()
    {
        $this->seed();

        $user = User::find(2); //staff

        $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/messages', [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data'])
            ->assertJson(['success' => true])
            ->assertJson(['data' => []])
            ->assertJsonCount(3, 'data.*'); //it's from seeder, tiga percakapan karena bs liat semua percakapan. bahkan customer to customer;

    }

    public function test_get_message_from_specific_person() {
        $this->seed();

        $user = User::find(1); //customer

        $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/messages/1-2', [], ['Accept' => 'application/json'])
            ->assertJsonStructure(['success', 'message', 'data'])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJson(['data' => []])
            ->assertJsonCount(5, 'data.*'); // 
        
        
        $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/messages/1-3', [], ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'data'])
            ->assertJson(['success' => true])
            ->assertJson(['data' => []])
            ->assertJsonCount(2, 'data.*'); // percakapan dengan admin
    }

    public function test_customer_cannot_access_other_user_chat(){
        $this->seed();

        $user = User::find(1); //customer

        $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/messages/2-3', [], ['Accept' => 'application/json'])
            ->assertJsonStructure(['message'])
            ->assertStatus(403)
            ->assertJson(['success' => false]); 
    }
    
    public function test_staff_can_access_other_user_chat(){
        $this->seed();

        $user = User::find(2); //customer

        $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/messages/1-3', [], ['Accept' => 'application/json'])
            ->assertJsonStructure(['message'])
            ->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data.*'); 
    }


}
