<?php

namespace Tests\Feature\Travel;

use App\Models\User; // Importe o User
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelOrderCreationTest extends TestCase
{
    use RefreshDatabase;


    public function test_an_authenticated_user_can_create_a_travel_order(): void
    {

        $user = User::factory()->create();

        $orderData = [
            'requester_name' => 'John Doe',
            'destination' => 'Paris, France',
            'start_date' => '2025-10-15',
            'end_date' => '2025-10-20',
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/travel-orders', $orderData);


        $response->assertStatus(201);


        $response->assertJsonFragment(['destination' => 'Paris, France']);


        $this->assertDatabaseHas('travel_orders', [
            'destination' => 'Paris, France',
            'user_id' => $user->id,
        ]);
    }


    public function test_a_guest_cannot_create_a_travel_order(): void
    {

        $orderData = [
            'requester_name' => 'John Doe',
            'destination' => 'Paris, France',
            'start_date' => '2025-10-15',
            'end_date' => '2025-10-20',
        ];


        $response = $this->postJson('/api/travel-orders', $orderData);


        $response->assertStatus(401);
    }
}
