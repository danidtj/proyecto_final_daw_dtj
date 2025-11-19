<?php
//require_once __DIR__ . '/Router.php';
//require_once __DIR__ . '/View.php';
//require_once dirname(__DIR__) . '/vendor/autoload.php';
use Bootstrap\Router;

// Define registered routes
$routes = [
    // Frontend routes
    'carta' => dirname(__DIR__) . '/views/frontend/carta.php',
    'contacto' => dirname(__DIR__) . '/views/frontend/contacto.php',
    'reserva' => dirname(__DIR__) . '/views/frontend/reserva.php',
    'home' => dirname(__DIR__) . '/views/frontend/index.php',
    'login' => dirname(__DIR__) . '/views/frontend/login.php',
    'admin' => dirname(__DIR__) . '/views/admin/admin.php',
    //'home' => __DIR__ . '/../views/frontend/index.php',
    
];


// Initialize router with routes
Router::initialize($routes);

