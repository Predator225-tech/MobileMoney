<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// 1. La page d'accueil (URL "/") redirige vers le formulaire de login
$routes->get('/', 'Client\AuthController::index');

// 2. Groupe Authentification
$routes->group('auth', function ($routes) {
    $routes->post('login', 'Client\AuthController::login');
    $routes->get('logout', 'Client\AuthController::logout');
});

// 3. Groupe Admin
$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'Operator\OperatorController::index');
    $routes->get('gains', 'Operator\OperatorController::showGains');

    // --- Gestion des Préfixes ---
    $routes->get('prefixes', 'Operator\PrefixeController::index');
    $routes->post('prefixes/store', 'Operator\PrefixeController::store');
    $routes->get('prefixes/delete/(:num)', 'Operator\PrefixeController::delete/$1');

    // --- Gestion des Types d'Opérations (AJOUTÉ ICI) ---
    $routes->get('types-operations', 'Operator\OperationController::index');
    $routes->post('types-operations/store', 'Operator\OperationController::store');
    $routes->post('types-operations/update/(:num)', 'Operator\OperationController::update/$1');
    $routes->get('types-operations/delete/(:num)', 'Operator\OperationController::delete/$1');

    // --- Gestion des barèmes de frais ---
    $routes->get('baremes', 'Operator\BaremeController::index');
    $routes->post('baremes/store', 'Operator\BaremeController::store');
    $routes->post('baremes/update/(:num)', 'Operator\BaremeController::update/$1');
    $routes->get('baremes/delete/(:num)', 'Operator\BaremeController::delete/$1');
});

// 4. Groupe Client
$routes->group('client', ['namespace' => 'App\Controllers\Client'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->post('deposit', 'DashboardController::deposit');
    $routes->post('withdraw', 'DashboardController::withdraw');
    $routes->post('transfer', 'DashboardController::transfer');
});
