<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Carbon\Carbon;

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
    public function editProfile(Request $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        return parent::responseSuccess(['user' => $user]);
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

    public function forgotPassword(Request $request){
        $email = $request->email;
        if(!$user = User::where('email', $email)->first()){
            return parent::responseError(404, 'Record not found');
        }
        
        $link = 'http://localhost:4200/reset-password?id=' . encrypt($user->user_id);

        $mail = new ForgotPassword;
        $mail->name = $user->name;
        $mail->link = $link;
        Mail::to($user->email)->send($mail);

        return parent::responseSuccess([
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function resetPassword(Request $request){
        $userId = decrypt($request->input('user_id'));
        if (!$user = User::find($userId)) {
            return parent::responseError(404, 'Record not found');
        }
        
        $user->password = Hash::make($request->password);
        $user->password_changed_at = Carbon::now();
        $user->save();

        $credentials = [
            'email' => $user->email,
            'password' => $request->password,
        ];
    
        if (!$access_token = auth()->attempt($credentials)) {
            return parent::responseError(401, 'Unauthorized');
        }

        return parent::responseSuccess([
            'access_token' => $access_token,
            'user' => auth()->user(),
        ]);
    }
}
