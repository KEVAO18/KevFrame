<?php

namespace App\Models;

use App\Http\Interfaces\PedidosInterface;
use DateTime;

class Pedidos implements PedidosInterface
{
    private int $id;
    private Usuario $usuario;
    private DateTime $fecha;
    private string $estado;
    private float $total;

    public function __construct(int $id, Usuario $usuario, DateTime $fecha, string $estado, float $total) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->fecha = $fecha;
        $this->estado = $estado;
        $this->total = $total;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function getFecha(): DateTime {
        return $this->fecha;
    }

    public function getEstado(): string {
        return $this->estado; 
    }

    public function getTotal(): float {
        return $this->total;
    }
}