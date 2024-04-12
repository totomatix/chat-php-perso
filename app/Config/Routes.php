<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 //Routes pour l'authentification
$routes->get('/', 'AuthController::index');

$routes->get('register', 'AuthController::register');

$routes->post('register', 'AuthController::save');

$routes->post('logIn', 'AuthController::check');

$routes->get('logOut', 'AuthController::logOut');

//Route pour l'affichage du chat
$routes->get('chat', 'ChatController::index');

//Route pour l'affichage de la conversation privée
$routes->get('chatPerso/(:num)', 'ChatController::chatPerso/$1');

//Routes pour l'affichage des messages
$routes->post('getMessage', 'ChatController::getMessage');

$routes->post('chatPerso/getMessage', 'ChatController::getMessage');

//Routes pour poster les messages
$routes->post('postMessage', 'ChatController::postMessage');

$routes->post('chatPerso/postMessage', 'ChatController::postMessage');

//Route pour enregistrer les fichiers joints
$routes->post('getFile', 'FileController::displayFile');

//Route pour afficher les fichiers joints
$routes->get('file/display/(:num)', 'FileController::display/$1');









