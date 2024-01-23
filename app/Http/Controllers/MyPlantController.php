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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(MyPlant $myPlant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MyPlant $myPlant)
    {
        //
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
