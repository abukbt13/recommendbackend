<?php

namespace App\Http\Controllers;

use App\Mail\Authenticate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'language_type' => 'required',
              'occupation' => 'required',
        ]);
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }
        $email = $request['email'];
        $OTP=rand(1,100000);
        $user= new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->otp=$OTP;
        $user->password=\Hash::make($request->password);
        $user->language_type=$request->language_type;
        $user->occupation=$request->occupation;
        $user->save();

            $data = [
                'subject' => 'Account Verification',
                'body' => 'Registration',
                'otp' => $OTP
            ];

            try {
                Mail::to($email)->send(new Authenticate($data));
                return response()->json(       [
                        'success' =>'Great check your email',
                        'message' =>'Check your email verification code'
                    ]);

            }catch (Exception $th){
                return response()->json([
                    'errors' =>'Invalid data received'
                ]);
            }

    }
    public function request_reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }
        $OTP=time();
        $email=$request->email;
        $user=User::where('email','=',$email)->first();
        $user->remember_token = $OTP;

        $user->update();

            $data = [
                'subject' => 'Account Verification',
                'body' => 'Registration',
                'otp' => $OTP
            ];

            try {
                Mail::to($email)->send(new Authenticate($data));
                return response()->json(       [
                        'success' =>'Great check your email',
                        'message' =>'Check your email verification code'
                    ]);

            }catch (Exception $th){
                return response()->json([
                    'errors' =>'Invalid data received'
                ]);
            }

    }
    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'otp' => 'required',
        ]);
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }
        $OTP=time();
        $email=$request->email;
        $otp=$request->otp;
        $user = User::where([
            ['email', '=', $email],
            ['remember_token', '=', $otp],
        ])->first();
        if($user){
            $user->password= \Hash::make($request->password);
            $user->update();
            return response([
                'status' => 'success',
                'errors' => 'The password was changed successfully'
            ]);
        }
      else{
          return response([
              'status' => 'failed',
              'errors' => 'Incorect details entered'
          ]);
      }



    }
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' => 'email',
            'otp' => 'required'
        ]);
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }
        else {
            $user=User::where('email','=',$request->email)->first();
            if($user){
                $user=User::where('otp','=',$request->otp)->first();
                if($user){
                    $datetime = date('Y-m-d H:i:s');
                    $user->email_verified_at = $datetime;
                    $user->status=1;
                    $token = $user->createToken('token')->plainTextToken;
                    $user->update();

                        return response()->json([
                            'status' =>'success',
                            'user' =>$user,
                            'token' =>$token,
                            'message' =>'Your account is now successfully verifed'
                        ]);


                }
                else {
                    return response()->json([
                        'status' =>'failed',
                        'message' =>'The credentials you entered are incoret ensure your details are correct'
                    ]);
                }
            }


        }
    }
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (auth()->attempt($data)) {
            $user = auth()->user();
            $status =$user->status;
            if ($status == 0){
                return response()->json([
                    'status' =>'failed',
                    'message' => 'Your Account is not verified check your email  address for verification',
                ]);
            }
            else{
                $token = $user->createToken('token')->plainTextToken;
                return response()->json([
                    'status' =>'success',
                    'token' => $token,
                    'user' => $user,
                    'message' => 'Successfully logged in',
                ]);
            }

        } else {
            return response()->json([
                'status' =>'failed',
                'error' => 'Enter correct details to log in'
            ]);
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
