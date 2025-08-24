<?php

namespace App\Http\Controllers;

use App\DTO\Enums\SearchProductEnum;
use App\Services\Search\SearchAddress;
use App\Services\Search\SearchProduct\SearchProduct;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SearchController extends Controller
{
    public function address(Request $request)
    {
        return response([
            'data' => SearchAddress::search($request->get('value'))
        ], 200);
    }

    public function products(Request $request)
    {
        $type = $request->type ?? 'full';

        throw_if(
            !SearchProductEnum::caseExists($type),
            new BadRequestHttpException(__('abortions.incorrectProductSearchType'))
        );

        return response([
            'data' => SearchProduct::search($request->get('value'), SearchProductEnum::fromValue($type))
        ], 200);
    }
}
