<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Hosting_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserCenteredController extends Controller
{

    public function recommenduser(){
        $language = Auth::user()->language_type;
        if($language == 'other'){

            $user= Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
                ->select('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type')
                ->where('hosting_details.type', '=', 'Frontend')
                ->groupBy('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type')
                ->orderBy('rating', 'desc')
                ->limit(3)
                ->get();
            $other=Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
                ->select('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type')
                ->where('hosting_details.type', '=', 'backend')
                ->groupBy('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type')
                ->orderBy('rating', 'desc')
                ->limit(3)
                ->get();
            return response()->json([
                'usercompanies'=>$user,
                'othercompanies'=>$other
            ]);

        }
        else{
            $user = Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
                ->select('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type', DB::raw('MAX(companies.rating) as rating'))
                ->where('hosting_details.type', '=', $language)
                ->groupBy('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type')
                ->orderBy('rating', 'desc')
                ->limit(3)
                ->get();

            $other= Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
                ->select('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type', DB::raw('MAX(companies.rating) as rating'))
                ->where('hosting_details.type', '!=', $language)
                ->groupBy('companies.company_name','companies.rating', 'companies.id', 'companies.company_logo', 'hosting_details.type')
                ->orderBy('rating', 'desc')
                ->limit(3)
                ->get();
            return response()->json([
                'usercompanies'=>$user,
                'othercompanies'=>$other
            ]);
        }







        //to be completed where the companies are grouped according to frequency


    }

    public function recommendlanguage()
{
    $language = Auth::user()->language;
    $hostings = Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
        ->select('companies.company_logo', 'hosting_details.company_name')
        ->groupBy('hosting_details.company_name')
        ->orderBy('companies.company_name', 'desc')
        ->limit(3)
        ->where('hosting_details.type', $language)
        ->get();

    return response()->json($hostings);
}
public function languageloved()
{
    $language = Auth::user()->language;
    $hostings = Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
        ->select('companies.company_logo', 'hosting_details.company_name')
        ->groupBy('hosting_details.company_name')
        ->orderBy('companies.company_name', 'desc')
        ->limit(2)
        ->where('hosting_details.type', $language)
        ->get();

    return response()->json($hostings);
}
public function languagealsoloveds()
{
    $language = Auth::user()->language;
    $hostings = Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
        ->select('companies.company_logo', 'hosting_details.company_name')
        ->limit(3)
        ->where('hosting_details.type', '!=', $language)
        ->get();

    return response()->json($hostings);
}
public function application(Request $request)
{
    $user_id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
        'company_name' => 'required',
        'description' => 'required',
    ]);

    // Check validation failure
    if (count($validator->errors())) {
        return response([
            'status' => 'failed',
            'errors' => $validator->errors()
        ], 422);
    }
    $application = new Application();
    $application->company_name = $request->company_name;
    $application->description = $request->description;
    $application->user_id = $user_id;
    $application->save();

    return response()->json($application);
}


}
