<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', function () {
//     throw new \CodeIgniter\Router\Exceptions\RedirectException('api/products');
// });

$routes->get('/', 'Products::index');

$routes->get('api', function () {
    throw new \CodeIgniter\Router\Exceptions\RedirectException('api/products');
});

$routes->group("api", function ($routes) {
    $routes->get("products", "Products::index");
    $routes->post("products", "Products::addProduct");
    $routes->post("products/searchproduct", "Products::searchProduct");
    $routes->put("products/(:segment)", "Products::updateProduct/$1");
    $routes->delete("products/(:segment)", "Products::deleteProduct/$1");
    $routes->get("products/count", "Products::countProduct");
    $routes->get("products/category/count/(:segment)", "Products::countProductByCategory/$1");
    $routes->get("products/category/(:segment)", "Products::getProductByCategory/$1");
    $routes->post("products/category/searchproduct", "Products::searchProductByCategory");
    $routes->get("products/(:segment)", "Products::getSingleProduct/$1");
    $routes->get("categories", "Categories::index");
    $routes->post("categories", "Categories::create");
    $routes->delete("categories/(:segment)", "Categories::remove/$1");
    // GAS
    $routes->get("gas/costumers", "Costumers::getAllCostumer");
    $routes->get("gas/notes/(:segment)", "GasNote::getAllNote/$1");
    $routes->get("gas/notes/count/(:segment)", "GasNote::countNote/$1");
    $routes->get("gas/notedetail/(:segment)", "GasNote::getDetail/$1");
    $routes->get("gas", "Gas::index");
    $routes->get("gas/(:segment)", "Gas::getSingleGas/$1");
    $routes->put("gas/note/(:segment)", "GasNote::update/$1");
    $routes->delete("gas/note/(:segment)", "GasNote::delete/$1");
    $routes->delete("gas/costumers/(:segment)", "Costumers::delete/$1");
    $routes->put("gas/statusnote/(:segment)", "GasNote::statusUpdate/$1");
    $routes->put("gas/(:segment)", "Gas::updateGasPrice/$1");
    $routes->post("gas/costumers", "Costumers::addCostumer");
    $routes->post("gas/create", "GasNote::create");
    $routes->post("gas/searchnotes", "GasNote::searchNotes");
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
