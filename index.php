<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    require __DIR__ . "/src/$class.php";
});

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

// echo json_encode($parts);
$database = new Database("localhost", "centraluniformes", "root", "");


switch ($parts[2]) {

    case "tipo_trabajos":
        $gateway = new TipoTrabajoGateway($database);
        $controller = new TipoTrabajoController($gateway);
        break;

    case "tipo_articulos":
        $gateway = new TipoArticuloGateway($database);
        $controller = new TipoArticuloController($gateway);
        break;

    case "clientes":
        $gateway = new ClienteGateway($database);
        $controller = new ClienteController($gateway);
        break;

    case "pedidos":
        $gateway = new PedidoGateway($database);
        $controller = new PedidoController($gateway);
        break;

    case "logos":
        $gateway = new LogoGateway($database);
        $controller = new LogoController($gateway);
        break;

    case "trabajos":
        $gateway = new TrabajoGateway($database);
        $controller = new TrabajoController($gateway);
        break;
        
}


$id = $parts[3] ?? null;



$database->getConnection();

$gateway = new TipoArticuloGateway($database);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);