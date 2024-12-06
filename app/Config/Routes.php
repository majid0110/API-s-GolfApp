<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
$routes->get('/checkAdmin','HomeController::login');
$routes->get('/Usersbyrole','HomeController::getUsersByRole');
$routes->get('/profile','HomeController::profile');
$routes->get('insertData',"HomeController::insertData");
$routes->get('GetTournamentScore','HomeController::TournamentScore');
$routes->get('getplayroundscore','HomeController::playRoundScore');








$routes->get('addGames','HomeController::addGames');
$routes->get('/RecoverPassword',"HomeController::RecoverPassword");