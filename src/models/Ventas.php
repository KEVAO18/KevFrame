<?php

namespace App\Models;

use App\Http\Interfaces\VentasInterface;
use DateTime;

class Ventas implements VentasInterface{
    private int $id;
    private int $producto;
    private int $cantidad;
    private string $factura;
    private DateTime $fecha;

    public function __construct(int $id, int $producto, int $cantidad, string $factura, DateTime $fecha) {
        $this->id = $id;
        $this->producto = $producto;
        $this->cantidad = $cantidad;   
        $this->factura = $factura;
        $this->fecha = $fecha; 
    }

    public function getId(): int{
        return $this->id; 
    }

    public function getProducto(): int{
        return $this->producto; 
    }

    public function getCantidad(): int{
        return $this->cantidad; 
    }

    public function getFactura(): string{
        return $this->factura;
    }

    public function getFecha(): DateTime{
        return $this->fecha;
    }

}