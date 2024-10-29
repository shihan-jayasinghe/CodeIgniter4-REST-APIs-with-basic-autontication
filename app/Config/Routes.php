<?php

use CodeIgniter\Router\RouteCollection;
//use App\Controllers\Api\ProductController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("product",["namespace"=>"App\Controllers\Api","filter"=>"basic_auth"],function($routes){
    
    //post
    $routes->post("add","ProductController::addProduct");

    //get
    $routes->get("list","ProductController::listAllProduct");

    //get
    $routes->get("(:num)","ProductController::productData/$1");

    //put
    $routes->put("(:num)","ProductController::updateProduct/$1");

    //delete
    $routes->delete("(:num)","ProductController::deleteProduct/$1");
});
