<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserType;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required'],
            'user_type' => ['required']
        ]);

        $userType = UserType::firstOrNew(['name' => $credentials['user_type']]);
        $userType->save();
        $userTypeId = $userType->id;

        $user = User::firstOrNew([
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password']),
            'user_type_id' => $userTypeId
        ]);
        $user->save();
        $token = $user->createToken('accessToken')->accessToken;

        return [
            'success' => true,
            'message' => 'User registered!',
            'token' => $token
        ];

    }
    
    public function login(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $t = $request->only(['email', 'password']);
        

        if (Auth::attempt($credentials)) {
            
            $token = Auth::user()->createToken('accessToken')->accessToken;
            return response()->json([
                'successs' => true,
                'token' => $token
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid Credential'
        ]);
    }

    public function logout(Request $request){
        $user = Auth::user()->token();
        $user->revoke();
        return [
            'success' => true,
            'message' => 'user logout!'
        ];
    }
    
    public function getUserList(Request $request){
        return User::get();
    }
}
