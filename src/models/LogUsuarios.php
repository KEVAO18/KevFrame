<?php

namespace App\Models;

use App\Http\Interfaces\LogUsuariosInterface;
use DateTime;

class LogUsuarios implements LogUsuariosInterface{
    private int $id;
    private int $usuario;
    private string $accion;
    private string $detalle;
    private DateTime $fecha;

    public function __construct(int $id, int $usuario, string $accion, string $detalle, DateTime $fecha) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->accion = $accion;
        $this->detalle = $detalle;
        $this->fecha = $fecha; 
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getUsuario(): int {
        return $this->usuario; 
    }

    public function getAccion(): string {
        return $this->accion;
    }

    public function getDetalle(): string {
        return $this->detalle;
    }

    public function getFecha(): DateTime {
        return $this->fecha;
    }
}