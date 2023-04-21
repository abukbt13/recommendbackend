<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Hosting_detail;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_name' => 'required',
            'url' => 'required',
            'company_logo' => 'required|image|mimes:jpeg,png,gif,jpg|max:2048',
        ]);

        // Check validation failure
        if (count($validator->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $validator->errors()
            ]);
        }
        $user_id = Auth::user()->id;
        $company = new Company();
        $company->company_name = $request->company_name;
        $company->url = $request->url;
        $company->rating = 1;
        $path = $request->file('company_logo')->store('public/company');
        $filename = basename($path);

        $company->company_logo = $filename;
        $company->user_id = $user_id;
        $company->path = $path;
        $company->save();
        return response()->json([
            'message' => 'You have successfully uploaded the company details'
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
            $initialrating= $details->rating;
            $details->rating=$initialrating+1;
            $details->update();
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
//select c.company_name, c.company_logo, h.language from hosting_details h join companies c on c.company_name=h.company_name where language='php';

    public function show_all_companies($language)
    {

        $bestfrontend =  DB::table('hosting_details as h')
                ->join('companies as c', 'c.company_name', '=', 'h.company_name')
                ->select('c.company_name','c.id','c.company_logo', 'h.language')
                ->where('h.language', '=', $language)
                ->get();

        return response()->json($bestfrontend);
    }
    public  function edit_company($id){
        $company=Company::where('id', $id)->get();
        return response()->json($company);
    }
 public  function edithostingdeail($id){
        $hostdetail=Hosting_detail::where('id', $id)->get();
        return response()->json($hostdetail);
    }


    public function all_frontend_host()
    {

        $bestfrontend = Company::join('hosting_details','hosting_details.company_name','=','companies.company_name')
        ->select('companies.company_name','companies.id','companies.company_logo','companies.rating')
        ->where('hosting_details.type','=', 'frontend')
        ->orderby('companies.rating','desc')
        ->get();

        return response()->json($bestfrontend);
    }

    public function all_backend_host()
    {

        $bestbackend = Company::join('hosting_details','hosting_details.company_name','=','companies.company_name')
        ->select('companies.company_name','companies.id','companies.company_logo','companies.rating')
        ->where('hosting_details.type','=', 'backend')
        ->orderby('companies.rating','desc')
        ->get();

        return response()->json($bestbackend);
    }
    public function count()
    {

       $users=User::count();
       $company=Company::count();
       $applications=Application::count();

        return response()->json(
            [
                'users'=>$users,
                'companies'=>$company,
                'applications'=>$applications
            ]
        );
    }
    }
