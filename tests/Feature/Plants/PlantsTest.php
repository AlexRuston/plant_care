<?php

namespace Tests\Feature\Plants;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Database\Seeders\PlantSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PlantsTest extends TestCase
{
    use RefreshDatabase;

    public function test_plant_list_can_be_retrieved()
    {
        // create user
        $user1 = User::factory()->create();

        // create access token
        $token = $user1->createToken('authtoken')->plainTextToken;

        $userTest = User::find(1);

        // get request to /plants containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])
            ->get('api/plants');

        // should see 200
        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJsonFragment([
            'name' => 'Spreading Hedgeparsley',
            'latin_name' => 'Torilis Arvensis',
            'water_frequency' => '14 days',
            'sunlight' => 'Partial Shade',
        ]);
    }
}
