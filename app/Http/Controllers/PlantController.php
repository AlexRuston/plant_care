<?php
/**
 * Test file(s):
 * PlantTest.php
 */
namespace App\Http\Controllers;

use App\Http\Resources\PlantResource;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(PlantResource::collection(Plant::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate posted fields
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latin_name' => ['required', 'string', 'max:255', 'unique:plants'],
            'water_frequency' => ['required', 'integer', 'between:0,100'],
            'sunlight' => ['required', 'integer', 'between:0,5'],
        ]);

        // create Plant
        $plant = Plant::create($validated);

        // build return array
        $response = [
            'plant' => $plant,
        ];

        return response(PlantResource::make($plant), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
        return response(PlantResource::make($plant), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        // validate posted fields
        $validated = $request->validate([
            'name' => ['string', 'max:255'],
            'latin_name' => ['string', 'max:255', 'unique:plants'],
            'water_frequency' => ['integer', 'between:0,100'],
            'sunlight' => ['integer', 'between:0,5'],
        ]);

        // create array of values to update
        $updateArray = $validated;

        // add updated_at field
        $updateArray['updated_at'] = date('Y-m-d H:i:s');

        // Persist
        $plant->update($updateArray);

        // build return array
        $returnArray = [
            'user' => PlantResource::make($plant),
            'updated' => $plant->getChanges(),
        ];

        return response($returnArray, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        //
    }
}
