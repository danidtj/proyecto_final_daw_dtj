<?php
namespace Bootstrap;
//require_once dirname(__DIR__) . '/vendor/autoload.php';

use Bootstrap\View;
class Router {
    private static $routes = [];

    // Initialize routes
    public static function initialize($routes) {
        self::$routes = $routes;
    }

    // Handle request and render the corresponding view
    public static function handleRequest($path) {
        if (isset(self::$routes[$path])) {
            //require_once __DIR__ . "/View.php"; // Ensure View class is included
            View::render(self::$routes[$path]);
        } else {
            echo "Error: Página '$path' no encontrada.";
        }
    }
}

?>
