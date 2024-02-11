<?php

namespace Tests\Feature\MyPlants;

use App\Models\Plant;
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

    public function test_validation_on_passing_a_user_id()
    {
        // get request to /plants containing token
        $response = $this->actingAs($this->testUser)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->testUserToken,
                'Accept' => 'application/json'
            ])
            ->post('api/my-plants', [
                'user_id' => 100,
            ]);

        $response->assertStatus(401);

        // should see the below JSON
        $response->assertJsonFragment([
            'message' => 'Bad request. You cannot pass a user_id because you cannot create a record against another user',
        ]);
    }

    public function test_validation_on_plant_id_must_exist()
    {
        // get request to /plants containing token
        $response = $this->actingAs($this->testUser)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->testUserToken,
                'Accept' => 'application/json'
            ])
            ->post('api/my-plants', [
                'plant_id' => 12832345,
            ]);

        $response->assertStatus(422);

        // should see the below JSON
        $response->assertJsonFragment([
            'message' => 'The selected plant id is invalid.',
        ]);
    }

    public function test_a_user_can_create_a_my_plant()
    {
        // get request to /plants containing token
        $response = $this->actingAs($this->testUser)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->testUserToken,
                'Accept' => 'application/json'
            ])
            ->post('api/my-plants', [
                'plant_id' => 2,
            ]);

        $response->assertCreated();

        // should see the below JSON
        $response->assertJsonFragment([
            'message' => 'plant added to user',
            'name' => Plant::find(2)->name,
        ]);
    }
}
