<?php
/**
 * Test file(s):
 * UsersTest.php
 */

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(UserResource::collection(User::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate posted fields
        $validated = $request->validate([
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
            'message' => 'user added',
            'user' => UserResource::make($user),
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return response(UserResource::make($user), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        /*
         * Email should be unique
         * If a user PUTs name through request with the existing name
         * So we tell the validation to ignore the unique rule when this happens
         * */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'string',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
        ]);

        // Create array of values to update
        $updateArray = $validated;
        $updateArray['updated_at'] = date('Y-m-d H:i:s');

        // Persist
        $user->update($updateArray);

        // Build return array to show new resource and the fields that were changed in the update
        $returnArray = [
            'user' => UserResource::make($user),
            'updated' => $user->getChanges(),
        ];

        return response($returnArray, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
