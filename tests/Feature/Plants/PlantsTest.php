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

        // get request to /plants containing token
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

    public function test_a_plant_can_be_created()
    {
        // get request to /plants containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->testUserToken,
            'Accept' => 'application/json'
        ])
            ->post('api/plants', [
                'name' => 'Test Plant',
                'latin_name' => 'Testicus Planticus',
                'water_frequency' => 2,
                'sunlight' => 0,
            ]);

        $response->assertCreated();

        // should see the below JSON
        $response->assertJsonFragment([
            'name' => 'Test Plant',
            'latin_name' => 'Testicus Planticus',
            'water_frequency' => '2 days',
            'sunlight' => 'Dark',
        ]);
    }

    public function test_a_plant_can_be_updated()
    {
        // get the plant we'll update
        $plant = Plant::all()->last();

        // get request to /plants] containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->testUserToken,
            'Accept' => 'application/json'
        ])
            ->put('api/plants/' . $plant->id, [
                'name' => 'Updated Plant',
                'latin_name' => 'Updaticus Planticilius',
            ]);

        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJsonFragment([
            "updated" => [
                'name' => 'Updated Plant',
                'latin_name' => 'Updaticus Planticilius',
            ],
        ]);
    }

    public function test_a_plant_update_validation_works()
    {
        // get the plant we'll update
        $plant = Plant::all()->last();

        // get request to /plants containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->testUserToken,
            'Accept' => 'application/json'
        ])
            ->put('api/plants/' . $plant->id, [
                'water_frequency' => 'string',
                'sunlight' => true,
            ]);

        $response->assertStatus(422);

        // should see the below JSON
        $response->assertJsonFragment([
            'message' => 'The water frequency field must be an integer.',
        ]);
    }

    public function test_a_plant_can_be_deleted()
    {
        // get the last plant
        $plant = Plant::all()->last();

        // get request to /users containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->testUserToken,
            'Accept' => 'application/json'
        ])
            ->delete('api/plants/' . $plant->id);

        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJsonFragment([
            'message' => 'Plant deleted successfully',
        ]);
    }
}
