<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $user = new User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // 登入用戶並創建 token
        $token = JWTAuth::fromUser($user);

        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$access_token = JWTAuth::attempt($credentials)) {
                $this->errorCode = 400;
                $this->errorMessage = trans('errors.' . $this->errorCode);
                return parent::responseError($this->errorCode, $this->errorMessage);
            }
        } catch (JWTException $e) {
            return parent::responseError(500, 'Server Error: Could not create access token');
        }
        return parent::responseSuccess(['access_token' => $access_token]);
    }


    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
