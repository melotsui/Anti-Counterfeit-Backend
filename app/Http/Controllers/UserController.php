<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = new User();
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));

        $user->save();

        // 生成 JWT token
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User created successfully',
            'token' => $token
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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

    //Verify Email
    public function verifyEmail(Request $request)
    {
        $user = auth()->user();
        $user->email_verified_at = now();
        $user->save();
        $access_token = JWTAuth::fromUser($user);
        return parent::responseSuccess(['access_token' => $access_token]);
    }
}
