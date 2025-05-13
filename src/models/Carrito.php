<?php

namespace App\Models;

use App\Http\Interfaces\CarritoInterface;
use DateTime;

class Carrito implements CarritoInterface {
    private int $id;
    private int $usuario;
    private DateTime $fechaCreacion;

    public function __construct(int $id, int $usuario, DateTime $fechaCreacion) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->fechaCreacion = $fechaCreacion; 
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsuario(): int{
        return $this->usuario;
    }

    public function getFechaCreacion(): DateTime {
        return $this->fechaCreacion; 
    }
    
}