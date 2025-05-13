<?php

namespace App\Models;

use App\Http\Interfaces\PedidoDetalleInterface;

class PedidoDetalle implements PedidoDetalleInterface{
    private int $id;
    private int $pedido;
    private int $producto;
    private int $cantidad;

    public function __construct(int $id, int $pedido, int $producto, int $cantidad) {
        $this->id = $id;
        $this->pedido = $pedido;
        $this->producto = $producto;
        $this->cantidad = $cantidad; 
    }

    public function getId(): int{
        return $this->id; 
    }

    public function getPedido(): int{
        return $this->pedido; 
    }

    public function getProducto(): int{
        return $this->producto; 
    }

    public function getCantidad(): int{
        return $this->cantidad;
    }
}