<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Company;
use App\Models\Hosting_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $update->company_name = $request->company_name;
        $update->url =$request->url;
        $update->company_logo = $imagename;
        $update->update();

        return response()->json('Successfully saved');
    }
    public  function delete_company($id){
        $delete=Company::find($id);
        $delete->delete();
        return response()->json("deleted successfully");
    }


}
