<?php
header('Access-Control-Allow-Origin: http://deviafernando.com');
header("Access-Control-Allow-Headers: Origin, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

use \Modules\Api\Controller\ApiController;
use \Core\Util;

require 'vendor/autoload.php';


try {
    set_error_handler('\Modules\Api\Controller\ApiController::getError');
    Util::loadConfig();

    $boots = [
        '\Modules\Api\Boot\RegisterLog'
    ];
    foreach ($boots as &$boot){
        $boot = new $boot();
        $boot->fire();
    }

    $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/', '\Modules\Api\Controller\ApiController.getStatus');

        $r->addGroup('/admin', function (\FastRoute\RouteCollector $r) {
            $r->addRoute('POST', '/auth', '\Modules\User\Controller\UserController.getLogin');
            $r->addRoute('GET', '/auth', '\Modules\User\Controller\UserController.doLogin');
        });


        $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
    });


    $response = false;
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];


    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            throw new Exception("Not Found", 404);
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            throw new Exception("Method not allowed", 405);
            break;
        case FastRoute\Dispatcher::FOUND:

            $vars = $routeInfo[2];
            $handler = explode('.', $routeInfo[1]);

            $controller = new $handler[0]();
            $response = $controller->{$handler[1]}();
            break;
        default:
            throw new Exception('Unexpected value', 500);
    }

    if (!$response) {
        throw new Exception('Error getting response');
    }
} catch (Exception $e){
    ApiController::registerLog("Error: ".$e->getCode().": ".$e->getMessage()." in ".$e->getFile().':'.$e->getLine());
    $response = ApiController::getResponse(code: $e->getCode(), message: "Error: ".$e->getCode().": ".$e->getMessage()." in ".$e->getFile().':'.$e->getLine());
}

header('Content-Type: application/json');
echo $response;
