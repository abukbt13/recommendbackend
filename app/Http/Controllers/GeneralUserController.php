<?php

namespace App\Http\Controllers;

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
}
