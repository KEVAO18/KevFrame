<?php

namespace App\Models;

use App\Http\Interfaces\PagosInterface;
use DateTime;

class Pagos implements PagosInterface{
    private int $id;
    private Pedidos $pedido;
    private MetodosPago $metodoPago;
    private float $monto;
    private DateTime $fecha;
    private EstadosPago $estado;

    public function __construct(int $id, Pedidos $pedido, MetodosPago $metodoPago, float $monto, DateTime $fecha, EstadosPago $estado) {
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

    public function getPedido(): Pedidos {
        return $this->pedido; 
    }

    public function getMetodoPago(): MetodosPago {
        return $this->metodoPago;
    }

    public function getMonto(): float {
        return $this->monto;
    }

    public function getFecha(): DateTime {
        return $this->fecha;
    }

    public function getEstado(): EstadosPago {
        return $this->estado;  
    }
}