<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');

$routes->get('auth', 'HomeController::index');

$routes->post('auth', 'HomeController::postMessage');

$routes->get('register', 'AuthController::register');

$routes->post('register', 'AuthController::save');

$routes->get('logOut', 'AuthController::logOut');

$routes->post('logIn', 'AuthController::check');

$routes->post('message', 'HomeController::postMessage');

$routes->post('getMessage', 'HomeController::getMessage');

$routes->get('chatPerso/(:num)', 'HomeController::chatPerso/$1');

$routes->post('chatPerso/(:num)', 'HomeController::postMessage');

$routes->post('chatPerso/message', 'HomeController::postMessage');

$routes->post('chatPerso/getMessage', 'HomeController::getMessage');

$routes->post('getFile', 'FileController::displayFile');

$routes->get('file/display/(:num)', 'FileController::display/$1', ['as' => 'display_file']);








