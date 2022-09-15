<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ReportTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_report_feedback()
    {
        $this->seed();

        $body = [
            'content' => "why is my package not received yet?"
        ];
        $userid = 1;
        $user = User::find($userid); // customer
        $response = $this->actingAs($user, 'api')
        ->json('POST', '/api/reports', $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'message'])
        ->assertJson(['success' => true]);

        $this->assertDatabaseHas('reports', [
            'content' => $body['content'],
            'reported_by' => $userid
        ]);
    }
    
    public function test_staff_cannot_report_feedback()
    {
        $this->seed();

        $body = [
            'content' => "why is my package not received yet?"
        ];
        $userid = 2; //staff
        $user = User::find($userid); // customer
        $response = $this->actingAs($user, 'api')
        ->json('POST', '/api/reports', $body, ['Accept' => 'application/json'])
        ->assertStatus(403)
        ->assertJsonStructure(['success', 'message'])
        ->assertJson(['success' => false ]);
    }

    public function test_reporting_another_customer(){
        $this->seed();

        $body = [
            'content' => "why is my package not received yet?",
            'reported_user_id' => 3
        ];
        $userid = 1;
        $user = User::find($userid); // customer
        $response = $this->actingAs($user, 'api')
        ->json('POST', '/api/reports', $body, ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'message'])
        ->assertJson(['success' => true]);

        $this->assertDatabaseHas('reports', [
            'content' => $body['content'],
            'reported_by' => $userid,
            'reported_user_id' => $body['reported_user_id']
        ]);
        
    }
    
    public function test_reporting_staff(){
        $this->seed();

        $body = [
            'content' => "why is my package not received yet?",
            'reported_user_id' => 2 //staff
        ];
        $userid = 1;
        $user = User::find($userid); // customer
        $response = $this->actingAs($user, 'api')
        ->json('POST', '/api/reports', $body, ['Accept' => 'application/json'])
        ->assertStatus(403)
        ->assertJsonStructure(['success', 'message'])
        ->assertJson(['success' => false]);

    }
}
