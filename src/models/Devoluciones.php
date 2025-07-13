<?php

namespace App\Models;

use App\Http\Interfaces\DevolucionesInterface;
use DateTime;

class Devoluciones implements DevolucionesInterface {
    private int $id;
    private Productos $producto;
    private Factura $factura;
    private string $motivo;
    private float $reembolso;
    private string $estado;
    private DateTime $fechaIngreso;
    private DateTime $fechaFinal;

    public function __construct(int $id, Productos $producto, Factura $factura, string $motivo, float $reembolso, string $estado, DateTime $fechaIngreso, DateTime $fechaFinal) {
        $this->id = $id;
        $this->producto = $producto;
        $this->factura = $factura;
        $this->motivo = $motivo;
        $this->reembolso = $reembolso;
        $this->estado = $estado;
        $this->fechaIngreso = $fechaIngreso;
        $this->fechaFinal = $fechaFinal;   
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getProducto(): Productos {
        return $this->producto;
    }

    public function getFactura(): Factura {
        return $this->factura;
    }

    public function getMotivo(): string {
        return $this->motivo;
    }

    public function getReembolso(): float {
        return $this->reembolso;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getFechaIngreso(): DateTime {
        return $this->fechaIngreso;
    }

    public function getFechaFinal(): DateTime {
        return $this->fechaFinal;  
    }
}