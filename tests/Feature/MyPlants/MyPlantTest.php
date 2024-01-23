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

        // disable requirement for auth token
        $this->withoutMiddleware();

        // create user
        $this->testUser = User::find(1);
    }

    public function test_users_plant_list_can_be_retrieved()
    {
        // get request to /my-plants
        $response = $this->actingAs($this->testUser)
            ->withHeaders([
            'Accept' => 'application/json'
        ])
            ->get('api/my-plants');

        // should see 200
        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJsonFragment([
            'name' => $this->testUser->plants[0]->name,
            'latin_name' => $this->testUser->plants[0]->latin_name,
            'last_watered' => $this->testUser->plants[0]->pivot->last_watered,
        ]);
    }
}
