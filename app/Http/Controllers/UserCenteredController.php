<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Hosting_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserCenteredController extends Controller
{
    public function recommendlanguage()
{
    $language = Auth::user()->language;
    $hostings = Hosting_detail::join('companies', 'companies.company_name', '=', 'hosting_details.company_name')
        ->select('companies.company_logo', 'hosting_details.company_name')
        ->groupBy('hosting_details.company_name')
        ->orderBy('companies.company_name', 'desc')
        ->limit(1)
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


}
