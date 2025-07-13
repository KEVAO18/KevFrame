<?php

namespace App\Http\Controllers;

use App\Core\SessionManager;
use App\Core\View;
use App\Http\Handlers\CarritoDetalleHandler;
use App\Http\Handlers\CarritoHandler;

class CarritoController
{
    public function mostrar(){
        // Lógica para mostrar carrito

        $sm = new SessionManager();
        $sm->start();

        // verificar si el usuario está logueado
        if (
            !$sm->has('user_id') ||
            !$sm->has('user_name') ||
            !$sm->has('user_email') ||
            !$sm->has('user_rol')
        ) header('Location: ' . $_ENV['APP_BASE_URL']);

        // si el usuario está logueado, mostrar carrito
        
        $carritoHandler = new CarritoHandler();
        $carrito = $carritoHandler->getByuserId($sm->get('user_id'));

        $carritoDetalleHandler = new CarritoDetalleHandler();
        
        // $carritoDetalles = $carritoDetalleHandler->getByCarritoId($carrito->getId());

        $sm->set('carrito', $carrito);

        View::render('componentes/cartera/carrito');

    }
}