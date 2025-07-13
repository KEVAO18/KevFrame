<?php

namespace App\Http\Interfaces;

interface CarritoDetalleInterface
{
    public function getId(): int;

    public function getCarrito(): CarritoInterface;

    public function getProducto(): ProductosInterface;

    public function getCantidad(): int;
}