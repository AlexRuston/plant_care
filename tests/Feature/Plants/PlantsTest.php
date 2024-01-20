<?php

namespace Tests\Feature\Plants;

use App\Models\Plant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PlantsTest extends TestCase
{
    protected $testUser;
    protected $testUserToken;

    public function setUp() : void {

        parent::setUp();
        // create user
        $this->testUser = User::factory()->create();

        // create access token
        $this->testUserToken = $this->testUser->createToken('authtoken')->plainTextToken;
    }
    public function test_plant_list_can_be_retrieved()
    {
        // get request to /plants containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->testUserToken,
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

    public function test_single_plant_can_be_retrieved()
    {
        $plant = Plant::first();

        // get request to /users containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->testUserToken,
            'Accept' => 'application/json'
        ])
            ->get('api/plants/' . $plant->id);

        // should see 200
        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJson([
            'id' => $plant->id,
            'name' => 'European Mountain Ash',
            'latin_name' => 'Sorbus Aucuparia',
            'water_frequency' => '7 days',
            'sunlight' => 'Partial Shade',
        ], $strict = false);
    }
}
