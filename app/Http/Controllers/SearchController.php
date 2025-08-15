<?php

namespace App\Http\Controllers;

use App\Services\Search\SearchAddress;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function address(Request $request)
    {
        $results = SearchAddress::search($request->get('value') ?? '');

        return response([
            'data' => $results
        ], 200);
    }
}
