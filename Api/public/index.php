<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Accept, X-Requested-With");
require_once __DIR__ . '/../settings.php';
require_once __DIR__ . '/../src/Messages/ErrorMessages.php';
require_once __DIR__ . '/../src/Response/Response.php';
require_once __DIR__ . '/../src/Entities/Contact.php';
require_once __DIR__ . '/../src/Controllers/ContactController.php';
require_once __DIR__ . '/../src/Database/DbConnectionInterface.php';
require_once __DIR__ . '/../src/Database/MysqlDatabase.php';

$routes = require_once __DIR__ . '/../src/routes/routes.php';

// Discover which route this request is for and call the appropriate controller.
$uri = $_SERVER['REQUEST_URI'];

foreach ($routes as $route => $routeController) {
    preg_match_all($route, $uri, $matches, PREG_SET_ORDER);
    if ($matches) {
        $controller = new $routeController['controller'];
        $method = $routeController['method'];
        $response = $controller->$method($matches);

        echo json_encode(['message' => $response->getMessage(), 'errors' => $response->getErrors()]);
        if ($response->getStatus()) {
            http_response_code($response->getStatus());
        }

        break;
    }
}
