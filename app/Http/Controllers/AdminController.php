<?php

namespace App\Http\Controllers;

use App\Mail\Authenticate;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public  function show_application()
    {
        $application = Application::where('status','0')->get();
        return response()->json($application);
    }
    public  function accept_application($id)
    {
        $application = Application::where('id', $id)->first();
        $user=User::where('id',$application->user_id)->first();
        $email=$user->email;
        $data = [
            'subject' => 'Company Acceptance',
            'body' => 'Application Acceptance',
            'otp' => '@2022'
        ];
        try {
            Mail::to($email)->send(new Authenticate($data));


            $application->status=1;
            $application->update();

            return response()->json( [
                'message' =>'Accepted the hosting application',
            ]);

        }catch (Exception $th){
            return response()->json([
                'errors' =>'Invalid data received'
            ]);
        }

    }
}
