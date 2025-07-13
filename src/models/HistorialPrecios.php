<?php

namespace App\Models;

use App\Http\Interfaces\HistorialPreciosInterface;
use DateTime;

class HistorialPrecios implements HistorialPreciosInterface {
    private int $id;
    private Productos $producto;
    private float $precio;
    private DateTime $fechaInicio;
    private DateTime $fechaFin;

    public function __construct(int $id, Productos $producto, float $precio, DateTime $fechaInicio, DateTime $fechaFin) {
        $this->id = $id;
        $this->producto = $producto;
        $this->precio = $precio;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin; 
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getProductoId(): Productos {
        return $this->producto; 
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    public function getFechaInicio(): DateTime {
        return $this->fechaInicio;
    }

    public function getFechaFin(): DateTime {
        return $this->fechaFin;
    }

}