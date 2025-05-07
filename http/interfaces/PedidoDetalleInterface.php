<?php

namespace App\Http\Interfaces;

interface PedidoDetalleInterface
{
    public function getId(): int;

    public function getPedido(): int;

    public function getProducto(): int;

    public function getCantidad(): int;
}