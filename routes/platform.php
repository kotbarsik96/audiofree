<?php

declare(strict_types=1);

use App\Models\Seo;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Products\ProductEditScreen;
use App\Orchid\Screens\Products\ProductsListScreen;
use App\Orchid\Screens\Products\ProductVariationScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Seo\SeoEditScreen;
use App\Orchid\Screens\Seo\SeoListScreen;
use App\Orchid\Screens\Taxonomy\TaxonomyEditScreen;
use App\Orchid\Screens\Taxonomy\TaxonomyListScreen;
use App\Orchid\Screens\TaxonomyValueEditScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
  ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
  ->name('platform.profile')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
  ->name('platform.systems.users.edit')
  ->breadcrumbs(fn(Trail $trail, $user) => $trail
    ->parent('platform.systems.users')
    ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
  ->name('platform.systems.users.create')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.systems.users')
    ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
  ->name('platform.systems.users')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('general.users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
  ->name('platform.systems.roles.edit')
  ->breadcrumbs(fn(Trail $trail, $role) => $trail
    ->parent('platform.systems.roles')
    ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
  ->name('platform.systems.roles.create')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.systems.roles')
    ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
  ->name('platform.systems.roles')
  ->breadcrumbs(fn(Trail $trail) => $trail
    ->parent('platform.index')
    ->push(__('general.roles'), route('platform.systems.roles')));

// Platform > Products
Route::screen('/products', ProductsListScreen::class)
  ->name('platform.products')
  ->breadcrumbs(
    fn(Trail $trail) => $trail
      ->parent('platform.index')
      ->push(__('general.products'), route('platform.products'))
  );

// Platform > Product
Route::screen('/product/{product?}', ProductEditScreen::class)
  ->name('platform.product.edit')
  ->breadcrumbs(
    function (Trail $trail, $product = null) {
      $route = $product ? route('platform.product.edit', $product->id) : null;

      return $trail
        ->parent('platform.products')
        ->push($product?->name ?? __('orchid.product.create'), $route);
    }
  );

// Platform > Product Variation
Route::screen('/product/{product}/variation/{variation?}', ProductVariationScreen::class)
  ->name('platform.product.variation.edit')
  ->breadcrumbs(
    function (Trail $trail, $product, $variation = null) {
      $route = $variation ? route('platform.product.variation.edit', $variation->id) : null;

      return $trail
        ->parent('platform.product.edit', $product)
        ->push($variation?->name ?? __('orchid.product.variation'), $route);
    }

  );

// Platform > Taxonomies
Route::screen('/taxonomies', TaxonomyListScreen::class)
  ->name('platform.taxonomies')
  ->breadcrumbs(function (Trail $trail) {
    return $trail
      ->parent('platform.index')
      ->push(__('orchid.taxonomy.taxonomies'), route('platform.taxonomies'));
  });

// Platform > Taxonomy
Route::screen('/taxonomy/{taxonomy?}', TaxonomyEditScreen::class)
  ->name('platform.taxonomy.edit')
  ->breadcrumbs(function (Trail $trail, $taxonomy = null) {
    $route = $taxonomy ? route('platform.taxonomy.edit', $taxonomy?->id) : null;

    return $trail
      ->parent('platform.taxonomies')
      ->push($taxonomy?->name ?? __('orchid.taxonomy.create'), $route);
  });

// Platform > Taxonomy value
Route::screen('/taxonomy-value/{taxonomy}/{tValue?}', TaxonomyValueEditScreen::class)
  ->name('platform.taxonomy.value.edit')
  ->breadcrumbs(function (Trail $trail, $taxonomy, $tvalue) {
    if ($tvalue)
      return $trail
        ->parent('platform.taxonomy.edit', $taxonomy)
        ->push($tvalue->value);
    else
      return $trail;
  });

// Platform > Seo
Route::screen('/seo', SeoListScreen::class)
  ->name('platform.seo')
  ->breadcrumbs(function (Trail $trail) {
    return $trail
      ->parent('platform.index')
      ->push('SEO', route('platform.seo'));
  });

Route::screen('/seo/page/{seoPage?}', SeoEditScreen::class)
  ->name('platform.seo.edit')
  ->breadcrumbs(function (Trail $trail, $seoPage = null) {
    $route = $seoPage ? route('platform.seo.edit', $seoPage->id) : null;

    return $trail
      ->parent('platform.seo')
      ->push($seoPage?->slug ?? __('orchid.seo.create'), $route);
  });