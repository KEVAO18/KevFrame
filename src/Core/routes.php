<?php

use App\Controllers\IndexController;
use App\Controllers\ProductoController;
use App\Controllers\CarritoController;
use App\Controllers\ContactoController;

// Listado de productos

// Ruta home
$app->get('/', function ($request, $response) {
    $response->getBody()->write("Bienvenido a la página principal");
    return $response;
})->setName('home');
