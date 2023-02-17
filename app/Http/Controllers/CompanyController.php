<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Hosting_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
   public function store(Request $request){
       $user_id=Auth::user()->id;
       $validator = Validator::make($request->all(), [
           'company_name' =>'required',
           'company_logo' =>'required',
       ]);
       // Check validation failure
       if (count($validator->errors())) {
           return response([
               'status' => 'failed',
               'errors' => $validator->errors()
           ], 422);
       }
        $company=new Company();
       $company->company_name=$request->company_name;
       $path = $request->file('company_logo')->store('public/company');
       $filename = basename($path);

       $company->company_logo=$filename;
       $company->user_id=$user_id;
       $company->path=$path;
       $company->save();
       return response()->json([
           'id' => $filename
       ]);

   }
   public function hosting_details(Request $request){
       $user_id=Auth::user()->id;
       $validator = Validator::make($request->all(), [
           'company_name' =>'required',
           'language' =>'required',
           'least_pricing_storage' =>'required',
           'can_host_free' =>'required',
           'storage' =>'required',
       ]);
       // Check validation failure
       if (count($validator->errors())) {
           return response([
               'status' => 'failed',
               'errors' => $validator->errors()
           ], 422);
       }
        $details=new Hosting_detail();
       $details->user_id=$user_id;
       $details->company_name=$request->company_name;
       $details->language=$request->language;
       $details->least_pricing_storage=$request->least_pricing_storage;
       $details->storage=$request->storage;
       $details->can_host_free=$request->can_host_free;
       $details->rating=1;


       $details->save();
       return response()->json([
           'details' => $details
       ]);
   }
    public function show(){
        $hostings=Company::all();
        return response()->json($hostings);
    }
    public function showphp(){
        $hostings=Company::all();
        return response()->json($hostings);
    }
}
