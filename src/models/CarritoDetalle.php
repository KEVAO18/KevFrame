<?php

namespace App\Models;

use App\Http\Interfaces\CarritoDetalleInterface;

class CarritoDetalle implements CarritoDetalleInterface {
    private int $id;
    private int $carrito;
    private int $producto;
    private int $cantidad;

    public function __construct(int $id, int $carrito, int $producto, int $cantidad){
        $this->id = $id;
        $this->carrito = $carrito;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
    }

    public function getId(): int{
        return $this->id; 
    }

    public function getCarrito(): int{
        return $this->carrito;
    }

    public function getProducto(): int{
        return $this->producto; 
    }

    public function getCantidad(): int{
        return $this->cantidad; 
    }

}