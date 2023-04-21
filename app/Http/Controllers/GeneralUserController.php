<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Hosting_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GeneralUserController extends Controller
{
    public  function bestfrontend(){
        $bestfrontend = Hosting_detail::join('languages', 'languages.company_name', '=', 'hosting_details.company_name')
            ->join('companies', 'companies.company_name', 'languages.company_name')
            ->select('hosting_details.company_name','companies.company_logo', DB::raw('sum(languages.rating) as max_rating'))
            ->groupBy('hosting_details.company_name')
            ->orderBy('max_rating', 'desc')
            ->where('hosting_details.type', '=','backend')
            ->first();

//        $company= Company::all()->where('company_name','=','$company_details.company_name')->first();
//        $company_id=$company->id;
        return response()->json($bestfrontend);
    }
    public  function bestbackend(){
        $bestfrontend = Hosting_detail::join('languages', 'languages.company_name', '=', 'hosting_details.company_name')
            ->join('companies', 'companies.company_name', 'languages.company_name')
            ->select('hosting_details.company_name','companies.company_logo', DB::raw('sum(languages.rating) as max_rating'))
            ->groupBy('hosting_details.company_name')
            ->orderBy('max_rating', 'desc')
            ->where('hosting_details.type', '=','frontend')
            ->first();
        return response()->json($bestfrontend);
    }
    public function search(Request $request){
        $searchTerm = $request['q'];
        $results =    Company::where('company_name', 'LIKE', '%' . $searchTerm . '%')->get(); //
//        dd($results);

    return response()->json($results);
    }
        public function show_all_companies(){
            $company= Company::all();

    return response()->json($company);

        }
        public function hosting_details(){
            $hostingdetails= Hosting_detail::all();

    return response()->json($hostingdetails);

        }

    public function update_company(Request $request, $id) {



        $update = Company::find($id);
        $imagename = "";

        if ($request->hasFile('new_image')) {
            $path = $request->file('new_image')->store('public/company');

            $oldImagePath = '/app/company/' . $update->company_logo;
            if (file_exists($oldImagePath)) {
                File::delete($oldImagePath);
            }

            $imagename = basename($path);
        } else {
            $imagename = $update->company_logo;
        }

        $allowed_exts = array("jpg", "jpeg", "png", "gif");

        if (in_array(strtolower(pathinfo($imagename, PATHINFO_EXTENSION)), $allowed_exts)) {
            $update->company_name = $request->company_name;
            $update->url =$request->url;
            $update->company_logo = $imagename;
            $update->update();
            return response()->json('Successfully saved');
        }
        else {
            return response()->json('accepted files are jpeg,png,gif,jpg only');

        }


    }
    public function update_hosting_details(Request $request, $id) {
        $update = Hosting_detail::find($id);
         $update->company_name = $request->company_name;
         $update->language = $request->language;
        $update->type =$request->type;
        $update->least_pricing_storage =$request->least_pricing_storage;
        $update->can_host_free =$request->can_host_free;
        $update->update();

        return response()->json('Successfully saved');
    }
    public  function delete_company($id){
        $delete=Company::find($id);
        $delete->delete();
        return response()->json("deleted successfully");
    }
    public  function delete_host_details($id){
        $delete=Hosting_detail::find($id);
        $delete->delete();
        return response()->json("deleted successfully");
    }


}
