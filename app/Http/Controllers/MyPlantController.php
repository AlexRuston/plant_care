<?php
/**
 * Test file(s):
 * MyPlant.php
 */
namespace App\Http\Controllers;

use App\Http\Resources\MyPlantResource;
use App\Models\MyPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(MyPlantResource::collection(Auth::user()->plants), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
         * we don't want users to be able to add a plant against another user
         * so we're 400-ing them if they try to pass the user_id field
         * */
        if($request->has('user_id')){
            // Build return array
            $response = [
                'message' => 'Bad request. You cannot pass a user_id because you cannot create a record against another user',
            ];

            return response($response, 401);
        }

        // validate posted fields
        $validated = $request->validate([
            'plant_id' => ['required', 'integer', 'exists:App\Models\Plant,id'], // checks the Plant model, id column
            'last_watered' => ['date'],
        ]);

        $insertArr = [
            'user_id' => Auth::user()->id,
            'plant_id' => $validated['plant_id'],
            'last_watered' => $validated['last_watered'] ?? date("Y-m-d H:i:s"),
        ];

        // create MyPlant
        $myPlant = MyPlant::create($insertArr);

        // build return array
        $response = [
            'message' => 'plant added to user',
            'my-plant' => MyPlantResource::make($myPlant),
        ];

        return response($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MyPlant $myPlant)
    {
        if (Auth::user()->cannot('view', $myPlant)) {
            abort(403);
        }
        return response(MyPlantResource::make($myPlant), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MyPlant $myPlant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MyPlant $myPlant)
    {
        //
    }
}
