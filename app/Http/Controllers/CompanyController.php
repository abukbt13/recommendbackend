<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Hosting_detail;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'company_logo' => 'required',
        ]);
        // Check validation failure
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $company = new Company();
        $company->company_name = $request->company_name;
        $company->rating = 1;
        $path = $request->file('company_logo')->store('public/company');
        $filename = basename($path);

        $company->company_logo = $filename;
        $company->user_id = $user_id;
        $company->path = $path;
        $company->save();
        return response()->json([
            'id' => $filename
        ]);

    }

    public function hosting_details(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'language' => 'required',
            'type' => 'required',
            'least_pricing_storage' => 'required',
            'can_host_free' => 'required',
            'storage' => 'required',
        ]);
        // Check validation failure
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ], 422);
        }
        else {
           $company_name = $request->company_name;
            $language = $request->language;
            $hostingdetails = Hosting_detail::where('company_name', $company_name)
                ->where('language', $language)
                ->first();
            if($hostingdetails){
                return response()->json([
                    'error' => 'The language exist in that company'
                ]);
            }
            $company_exists = Language::where('company_name',$company_name);
            if($company_exists){
                $addrating=Company::where('company_name',$company_name)->first();
                $rating=$addrating->rating;
                $addrating->rating=$rating+1;
                $addrating->update();


                $details = new Hosting_detail();
                $details->user_id = $user_id;
                $details->company_name = $company_name ;
                $details->type = $request->type;
                $details->language = $language;
                $details->least_pricing_storage = $request->least_pricing_storage;
                $details->storage = $request->storage;
                $details->can_host_free = $request->can_host_free;
                $details->rating = 1;


                $details->save();
                return response()->json([
                    'details' => $details
                ]);
            }
            else{
                $details = new Hosting_detail();
                $details->user_id = $user_id;
                $details->company_name = $company_name ;
                $details->type = $request->type;
                $details->language = $language;
                $details->least_pricing_storage = $request->least_pricing_storage;
                $details->storage = $request->storage;
                $details->can_host_free = $request->can_host_free;
                $details->rating = 1;


                $details->save();
                return response()->json([
                    'details' => $details
                ]);
            }

        }
    }

    public function showrandom()
    {
        $hostings = DB::table('companies')->inRandomOrder()->Limit(18)->get();

        return response()->json($hostings);
    }
// public function showothers()
//    {
//        $hostings = DB::table('companies')->inRandomOrder()->Limit(15)->get();
//
//        return response()->json($hostings);
//    }

    public function showphp()
    {
        $hostings = Company::all();
        return response()->json($hostings);
    }
    public function language(Request $request)
    {
        $user_id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $name= $request->name;
        $company_name= $request->company_name;

        $language = Language::where('name', $name)->first();
        if($language){
            return response()->json([
                'status' => 'failed',
                'message' =>'Language exist enter new language'
            ]);
        }

        $language=new Language();
        $language->user_id = $user_id;
        $language->name = $name;
        $language->rating = 1;
        $language->save();
        return response()->json([
            'status' => 'success',
            'message' =>'Successfully inserted',
            'details' => $language
        ]);
    }

    public function besthosting()
    {
        $sumData =Hosting_detail::selectRaw('company_name, SUM(least_pricing_storage) as total')
            ->groupBy('company_name')
            ->orderBy('total', 'Desc')
            ->Limit(3)
            ->get(1);
        return response()->json($sumData);

    }
    public function select_language(){
        $language=Language::all();
        return response()->json([
            'details' => $language
        ]);
    }
    public function select_company(){
        $abu=Company::all();
        return response()->json($abu);
    }
    public function companydetails($id){
            $details=Company::where('id',$id)->first();
        return response()->json([
                'details' => $details,
            ]);
    }
    public function companydetailslanguages($id){
            $details=Company::where('id',$id)->first();
            $companydetails=Hosting_detail::where('company_name','=',$details->company_name)->get();
        return response()->json([
                'company_details' => $companydetails
            ]);
    }
    public function showmoreCompanydetails($name){
            $details=Company::where('company_name',$name)->first();
            $newrating=$details->rating;
             $details->rating=$newrating+1;
            $details->update();
            return response ()->json($details);
    }
    public function specificlanguages(){
            $details=Language::all();

            return response ()->json($details);
    }
}
