<?php

namespace App\Models;

use App\Http\Interfaces\PagosInterface;
use DateTime;

class Pagos implements PagosInterface{
    private int $id;
    private int $pedido;
    private int $metodoPago;
    private float $monto;
    private DateTime $fecha;
    private int $estado;

    public function __construct(int $id, int $pedido, int $metodoPago, float $monto, DateTime $fecha, int $estado) {
        $this->id = $id;
        $this->pedido = $pedido;
        $this->metodoPago = $metodoPago;
        $this->monto = $monto;
        $this->fecha = $fecha;
        $this->estado = $estado; 
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getPedido(): int {
        return $this->pedido; 
    }

    public function getMetodoPago(): int {
        return $this->metodoPago;
    }

    public function getMonto(): float {
        return $this->monto;
    }

    public function getFecha(): DateTime {
        return $this->fecha;
    }

    public function getEstado(): int {
        return $this->estado;  
    }
}