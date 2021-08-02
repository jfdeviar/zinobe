<?php
header('Access-Control-Allow-Origin: http://deviafernando.com');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Expose-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

use \Modules\Api\Controller\ApiController;
use \Core\Util;
use \FastRoute\RouteCollector;

require 'vendor/autoload.php';


try {
    set_error_handler('\Modules\Api\Controller\ApiController::getError');

    $boots = [
        '\Modules\Api\Boot\LoadConfig',
        '\Modules\Api\Boot\ConnectDatabase',
        '\Modules\Api\Boot\RegisterLog',
        '\Modules\User\Boot\CheckLogin',
    ];

    foreach ($boots as &$boot){
        $boot = new $boot();
        $boot->fire();
    }
    $dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
        $r->addRoute('GET', '/', '\Modules\Api\Controller\ApiController.getStatus');
        $r->addRoute('OPTIONS', '/', '\Modules\Api\Controller\ApiController.getStatus');

        $r->addGroup('/auth', function (RouteCollector $r) {
            $r->addRoute('GET', '', '\Modules\User\Controller\UserController.getLogin');
            $r->addRoute('POST', '/', '\Modules\User\Controller\UserController.doLogin');
            $r->addRoute('POST', '/register', '\Modules\User\Controller\UserController.doRegister');
            $r->addRoute('POST', '/confirm', '\Modules\User\Controller\UserController.doConfirm');
        });

        $r->addGroup('/records', function (RouteCollector $r) {
            $r->addRoute('GET', '', '\Modules\User\Controller\RecordController.getItems');
            $r->addRoute('GET', '/{slug}', '\Modules\User\Controller\RecordController.getItem');
            $r->addRoute('POST', '', '\Modules\User\Controller\RecordController.doRegister');
            $r->addRoute('PUT', '/{id}', '\Modules\User\Controller\RecordController.doUpdate');
            $r->addRoute('DELETE', '/{id}', '\Modules\User\Controller\RecordController.doRemove');

        });




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
            throw new Exception("Página no encontrada", 404);
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            throw new Exception("Método no disponible", 405);
            break;
        case FastRoute\Dispatcher::FOUND:
            $vars = $routeInfo[2] ?? null;
            $handler = explode('.', $routeInfo[1]);
            $controller = new $handler[0]();

            if ($vars){
                $response = $controller->{$handler[1]}($vars);
            } else {
                $response = $controller->{$handler[1]}();
            }


            Util::$database->pdo->commit();
            break;
        default:
            throw new Exception('Petición invalida', 400);
    }

    if (!$response) {
        throw new Exception('Error del servidor. Contacte al administrador.');
    }
} catch (Exception $e){
    Util::$database->pdo->rollback();
    ApiController::registerLog("Error: ".$e->getCode().": ".$e->getMessage()." in ".$e->getFile().':'.$e->getLine());
    $response = ApiController::getResponse(code: $e->getCode(), message: "Error: ".$e->getMessage().((Util::$config['debug'] ?? false) ? " in ".$e->getFile().':'.$e->getLine().' ('.$e->getCode().')' : ''));
}

header('Content-Type: application/json');
echo $response;
