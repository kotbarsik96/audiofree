<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Product\ProductVariation;
use App\Models\Seo;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeoController extends Controller
{
    public function getPageInfo(string $slug, Request $request)
    {
        $seoPage = null;

        switch ($slug) {
            case 'product':
                $seoPage = $this->getProductPageInfo($request);
                break;
            default:
                $seoPage = Seo::where('slug', $slug)->first(['title', 'description']);
                break;
        }

        throw_if(!$seoPage, new NotFoundHttpException(__('abortions.pageNotFound')));

        return [
            'data' => $seoPage
        ];
    }

    public function getProductPageInfo(Request $request)
    {
        $product = Product::where('slug', $request->get('product'))
            ->first();
        $variation = ProductVariation::where('slug', $request->get('product_variation'))
            ->where('product_id', $product?->id)
            ->first();

        if (!$product)
            return null;

        $title = $product->name;
        if($variation) $title .= ' – ' . $variation->name;
        $title .= ' | AudioFree';

        return [
            'title' => $title,
            'description' => $title . ': ' . $product->description_seo
        ];
    }
}
