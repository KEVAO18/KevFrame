<?php

namespace App\Http\Interfaces;

interface PedidoDetalleInterface
{
    public function getId(): int;

    public function getPedido(): PedidosInterface;

    public function getProducto(): ProductosInterface;

    public function getCantidad(): int;
}