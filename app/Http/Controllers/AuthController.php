<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($user = User::find($request->input('email'))) {
            parent::responseError(500, 'Email already exists');
        }
        $user = new User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $access_token = JWTAuth::fromUser($user);

        $link = 'http://localhost:4200/email-verification?token=' . $access_token;

        $mail = new EmailVerification;
        $mail->name = $user->name;
        $mail->link = $link;
        Mail::to($user->email)->send($mail);

        return parent::responseSuccess(['access_token' => $access_token]);
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
        $user = auth()->user();
        if ($user->email_verified_at == null) {
            $link = 'http://localhost:4200/email-verification?token=' . $access_token;

            $mail = new EmailVerification;
            $mail->name = $user->name;
            $mail->link = $link;
            Mail::to($user->email)->send($mail);
        }
        return parent::responseSuccess([
            'access_token' => $access_token,
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    public function me()
    {
        if (auth()->user() != null)
            return parent::responseSuccess(auth()->user());
        else
            return parent::responseError(500, null);
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
        return parent::responseSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}