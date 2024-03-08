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
                'message' => 'You cannot pass a user_id because you cannot create a record against another user',
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
        // guard against a user guessing an id and retrieving another users my_plant
        if (Auth::user()->cannot('view', $myPlant)) {
            abort(403);
        }

        return response(MyPlantResource::make($myPlant), 200);
    }

    /**
     * Update the specified resource in storage.
     * this will probably only be used to update when the plant was last_watered
     */
    public function update(Request $request, MyPlant $myPlant)
    {
        // guard against a user updating another users my_plant
        if (Auth::user()->cannot('update', $myPlant)) {
            abort(403);
        }

        // validate posted fields
        $validated = $request->validate([
            'last_watered' => ['date'],
        ]);

        // create array of values to update
        $updateArray = $validated;

        // add updated_at field
        $updateArray['updated_at'] = date('Y-m-d H:i:s');

        // update MyPlant
        $myPlant->update($updateArray);

        // build return array
        $response = [
            'my-plant' => MyPlantResource::make($myPlant),
            'updated' => $myPlant->getChanges(),
        ];

        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MyPlant $myPlant)
    {
        // guard against a user deleting another users my_plant
        if (Auth::user()->cannot('delete', $myPlant)) {
            abort(403);
        }

        $myPlant->delete();

        return response([
            'message' => 'Plant deleted successfully',
        ], 200);
    }
}
