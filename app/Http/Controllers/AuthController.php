<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user= new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=\Hash::make($request->password);
        $user->language=$request->language;
        $user->occupation=$request->occupation;
        $user->save();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'You have registered successfully'
        ], 200);
    }
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($data)) {
            $user = auth()->user();
            $token = $user->createToken('token')->plainTextToken;            return response()->json([
                'token' => $token,
                'user' => $user,
                 'message' => 'Successfully logged in',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Credentials do not match'
            ], 200);
        }
    }

    public function user_details(Request $request)
    {
        $user = Auth::user();
        return $user;
    }

    public function logoutApi()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
}
