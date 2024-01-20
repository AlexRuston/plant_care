<?php

namespace Tests\Feature\Users;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    public function test_user_list_can_be_retrieved()
    {
        // create a user
        $user1 = User::factory()->create();

        // create access token for user 1
        $token = $user1->createToken('authtoken')->plainTextToken;

        // get request to /users containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])
            ->get('api/users');

        // should see 200
        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJsonFragment([
            'id' => $user1->id,
            'name' => $user1->name,
            'email' => $user1->email,
        ], $strict = false);
    }

    public function test_single_user_can_be_retrieved()
    {
        // create a user
        $user = User::factory()->create();

        // create access token
        $token = $user->createToken('authtoken')->plainTextToken;

        // get request to /users containing token
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])
            ->get('api/users/' . $user->id);

        // should see 200
        $response->assertStatus(200);

        // should see the below JSON
        $response->assertJson([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ], $strict = false);
    }

    public function test_a_users_data_can_be_updated()
    {
        // create a user
        $user = User::factory()->create();

        // create access token
        $token = $user->createToken('authtoken')->plainTextToken;

        /*
         * put request to users
         * updating the name and email
         * */
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])
            ->put('api/users/' . $user->id, [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);


        // should see 201
        $response->assertStatus(201);

        /*
         * updating a user returns us the new user model
         * and a list of the fields that were updated in the request
         * */
        $response->assertJsonFragment([
            'user' => [
                'id' => $user->id,
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
            'updated' => [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ],
        ]);
    }
}
