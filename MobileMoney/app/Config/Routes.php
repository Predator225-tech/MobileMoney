<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->group('admin', function($routes) {
    
    // Dashboard et Gains
    $routes->get('dashboard', 'Operator\OperatorController::index');
    $routes->get('gains', 'Operator\OperatorController::showGains');

    // Gestion des Préfixes (tout est sous Operator)
    $routes->get('prefixes', 'Operator\PrefixeController::index');
    $routes->post('prefixes/store', 'Operator\PrefixeController::store');
    $routes->get('prefixes/delete/(:num)', 'Operator\PrefixeController::delete/$1');

    // Gestion des barèmes de frais
    $routes->get('baremes', 'Operator\BaremeController::index');
    $routes->get('baremes/(:num)', 'Operator\BaremeController::index/$1');
    $routes->post('baremes/store', 'Operator\BaremeController::store');
    $routes->post('baremes/update/(:num)', 'Operator\BaremeController::update/$1');
    $routes->get('baremes/delete/(:num)', 'Operator\BaremeController::delete/$1');
    
});