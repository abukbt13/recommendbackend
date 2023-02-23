<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'language' => 'required',
              'occupation' => 'required',
        ]);
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }
        $user= new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=\Hash::make($request->password);
        $user->language=$request->language;
        $user->occupation=$request->occupation;
        $user->save();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'status' =>'success',
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
