<?php

namespace App\Models;

use App\Http\Interfaces\CarritoDetalleInterface;

class CarritoDetalle implements CarritoDetalleInterface {
    private int $id;
    private Carrito $carrito;
    private Productos $producto;
    private int $cantidad;

    public function __construct(int $id, Carrito $carrito, Productos $producto, int $cantidad){
        $this->id = $id;
        $this->carrito = $carrito;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
    }

    public function getId(): int{
        return $this->id; 
    }

    public function getCarrito(): Carrito{
        return $this->carrito;
    }

    public function getProducto(): Productos{
        return $this->producto; 
    }

    public function getCantidad(): int{
        return $this->cantidad; 
    }

}