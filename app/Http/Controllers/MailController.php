<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\Authenticate;

class MailController extends Controller
{
  public function index(Request $request){
      $data = [
          'subject' => 'Learning Mail',
          'body' => 'Hello from mail'
      ];
      try {
          Mail::to('finetectsolutions@gmail.com')->send(new Authenticate($data));
          return response()->json(['Great check your email']);

      }catch (Exception $th){
          return response()->json(['Something went wrong']);
      }
  }
}
