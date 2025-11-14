<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\AboutController;
use App\Controllers\Admin\PostController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\DashboardController;

/**
 * @var RouteCollection $routes
 */

$routes->get('sitemap.xml', 'SitemapController::index');
$routes->get('/', 'Home::index');
$routes->get('privacy-policy', 'Home::privacy');
$routes->get('terms-and-conditions', 'Home::terms');
// Guest Routes
$routes->get('search', 'SearchController::index');
$routes->get('category-list', 'ListCategoryController::index');
$routes->get('post/(:any)', 'PostItemController::show/$1');
$routes->get('category/(:any)', 'CategoryItemController::show/$1');
$routes->get('archive', 'ArchiveController::index');
$routes->get('archive/(:num)/(:num)', 'ArchiveController::show/$1/$2');

$routes->group('admin', ['filter' => 'group:admin'], static function ($routes) {
    $routes->get('', 'Admin\DashboardController::index');

    // Profile
    $routes->get('profile', 'Admin\ProfileController::index');
    $routes->post('profile/updateProfile', 'Admin\ProfileController::updateProfile');
    $routes->post('profile/changePassword', 'Admin\ProfileController::changePassword');


    // Posts
    $routes->get('posts', 'Admin\PostController::index');
    $routes->get('posts/create', 'Admin\PostController::create');
    $routes->post('posts/store', 'Admin\PostController::store');
    $routes->get('posts/edit/(:num)', 'Admin\PostController::edit/$1');
    $routes->post('posts/update', 'Admin\PostController::update');
    $routes->get('posts/delete/(:num)', 'Admin\PostController::delete/$1');


    // Categories
    $routes->get('categories', 'Admin\CategoryController::index');
    $routes->get('categories/create', 'Admin\CategoryController::create');
    $routes->post('categories/store', 'Admin\CategoryController::store');
    $routes->get('categories/edit/(:num)', 'Admin\CategoryController::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\CategoryController::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\CategoryController::delete/$1');
});

service('auth')->routes($routes);