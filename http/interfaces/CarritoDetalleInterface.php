<?php

namespace App\Http\Interfaces;

interface CarritoDetalleInterface
{
    public function getId(): int;

    public function getCarrito(): int;

    public function getProducto(): int;

    public function getCantidad(): int;
}