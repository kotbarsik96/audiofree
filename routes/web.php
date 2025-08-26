<?php
use App\Models\Product;
?>
<?php

use App\Models\Product\ProductVariation;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

if (env('DEV_MODE') && env('DEV_MODE') !== 'false') {
    Route::get('/test', function () {
        $searchValue = request()->get('search');

        return Product::join('product_variations', 'product_variations.product_id', '=', 'products.id')
            ->join('taxonomy_values as brands', 'brands.id', '=', 'products.brand_id')
            ->join('taxonomy_values as categories', 'categories.id', '=', 'products.category_id')
            ->join('taxonomy_values as types', 'types.id', '=', 'products.type_id')
            ->select([
                'products.id as product_id',
                'products.name as product_name',
                'products.slug as product_slug',
                'products.description as description',
                'product_variations.id as variation_id',
                'product_variations.name as variation_name',
                'product_variations.slug as variation_slug',
                'brands.value as brand',
                'categories.value as category',
                'types.value as type'
            ])
            ->whereRaw('MATCH(brands.value) AGAINST(? IN BOOLEAN MODE)', [$searchValue])
            ->orWhereRaw('MATCH(categories.value) AGAINST(? IN BOOLEAN MODE)', [$searchValue])
            ->orWhereRaw('MATCH(types.value) AGAINST(? IN BOOLEAN MODE)', [$searchValue])
            ->orWhereRaw('MATCH(products.name, products.slug, products.description) AGAINST(? IN BOOLEAN MODE)', [$searchValue])
            ->orWhereRaw('MATCH(product_variations.name, product_variations.slug) AGAINST(? IN BOOLEAN MODE)', [$searchValue])
            ->get();
    });
}

require __DIR__.'/auth.php';
