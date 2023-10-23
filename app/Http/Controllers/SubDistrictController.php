<?php

namespace App\Http\Controllers;

use App\Models\SubDistrict;
use Illuminate\Http\Request;

class SubDistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $sub_districts = SubDistrict::all();
        return parent::responseSuccess(['sub_districts' => $sub_districts]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // Get Sub-Districts by District ID
    public function getSubDistrictsByDistrictId($district_id)
    {
        if ($district_id == 0) {
            $sub_districts = SubDistrict::all();
            return parent::responseSuccess(['sub_districts' => $sub_districts]);
        }
        $sub_districts = SubDistrict::where('district_id', $district_id)->get();
        return parent::responseSuccess(['sub_districts' => $sub_districts]);
    }
}