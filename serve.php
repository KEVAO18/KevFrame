<?php
require __DIR__ . '/vendor/autoload.php';

use Slim\Factory\AppFactory;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();

// Configurar manejo de errores
$app->addErrorMiddleware(false, false, false);

// Incluir rutas de la aplicación
require __DIR__.'/src/Core/routes.php';

$app->run();
?>