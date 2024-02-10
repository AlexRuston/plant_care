<?php

namespace Tests\Feature\MyPlants;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MyPlantTest extends TestCase
{
    protected $testUser;
    protected $testUserToken;

    public function setUp() : void {

        parent::setUp();

        // create user
        $this->testUser = User::find(1);
    }

    public function test_users_plant_list_can_be_retrieved()
    {
        // get request to /my-plants
        $response = $this->actingAs($this->testUser)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->testUserToken,
                'Accept' => 'application/json'
        ])
            ->get('api/my-plants');

        // should see 200
        $response->assertStatus(200);
    }

    public function test_a_single_users_plant_can_be_retrieved()
    {
        // get request to /my-plants
        $response = $this->actingAs($this->testUser)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->testUserToken,
                'Accept' => 'application/json'
        ])
            ->get('api/my-plants/' . $this->testUser->plants[1]->id);

        // should see 200
        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJsonFragment([
            'id' => $this->testUser->plants[1]->id,
            'last_watered' => $this->testUser->plants[1]->last_watered,
        ]);
    }
}
