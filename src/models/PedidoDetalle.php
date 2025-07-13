<?php

namespace App\Models;

use App\Http\Interfaces\PedidoDetalleInterface;

class PedidoDetalle implements PedidoDetalleInterface{
    private int $id;
    private Pedidos $pedido;
    private Productos $producto;
    private int $cantidad;

    public function __construct(int $id, Pedidos $pedido, Productos $producto, int $cantidad) {
        $this->id = $id;
        $this->pedido = $pedido;
        $this->producto = $producto;
        $this->cantidad = $cantidad; 
    }

    public function getId(): int{
        return $this->id; 
    }

    public function getPedido(): Pedidos{
        return $this->pedido; 
    }

    public function getProducto(): Productos{
        return $this->producto; 
    }

    public function getCantidad(): int{
        return $this->cantidad;
    }
}