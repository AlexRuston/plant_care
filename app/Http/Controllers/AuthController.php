<?php
/**
 * Test file(s):
 * RegistrationTest.php
 * AuthenticationTest.php
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * creates a user in the DB
     *
     * @return Response
     * returns user model
     * returns token
     */
    public function register(Request $request)
    {
        // Validate posted fields
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['integer', 'exists:roles,id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create access token
        $token = $user->createToken('authtoken')->plainTextToken;

        // Build return array
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * logs a user in
     *
     * @return Response
     * returns user model
     * returns token
     */
    public function login(Request $request)
    {
        // Validate posted fields
        $fields = $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Password incorrect'
            ], 401);
        }

        // Create token
        $token = $user->createToken('authtoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * logs a user out by deleting all their exisitng tokens
     * i've chosen to remove all tokens rather than just the active one to stop someone holding onto an inactive token
     *
     * @return Response
     * returns user model
     * returns token
     */
    public function logout(Request $request)
    {
        // Delete all tokens
        auth()->user()->tokens()->delete();

        $response = [
            'message' => 'Logged out'
        ];

        return response($response, 201);
    }
}
