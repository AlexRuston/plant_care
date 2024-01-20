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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'latin_name' => ['required', 'string', 'max:255', 'unique:plants'],
            'water_frequency' => ['required', 'integer', 'between:0,5'],
            'sunlight' => ['required', 'integer', 'between:0,5'],
        ]);

        // create Plant
        $plant = Plant::create([
            'name' => $request->name,
            'latin_name' => $request->latin_name,
            'water_frequency' => $request->water_frequency,
            'sunlight' => $request->sunlight,
        ]);

        // build return array
        $response = [
            'plant' => $plant,
        ];

        return response($response, 201);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plant $plant)
    {
        //
    }
}
