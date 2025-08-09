<?php

namespace App\Http\Controllers;

use App\Models\Seo;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeoController extends Controller
{
    public function getPageInfo(string $slug)
    {
        $seoPage = Seo::where('slug', $slug)->first(['title', 'description']);

        throw_if(!$seoPage, new NotFoundHttpException(__('abortions.pageNotFound')));

        return $seoPage;
    }
}
