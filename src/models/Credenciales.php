<?php

namespace App\Models;

use App\Http\Interfaces\CredencialesInterface;

class Credenciales implements CredencialesInterface{
    private int $id;
    private int $usuario;
    private int $tipo;

    public function __construct(int $id, int $usuario, int $tipo) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->tipo = $tipo;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsuario(): int {
        return $this->usuario;
    }

    public function getTipo(): int {
        return $this->tipo;
    }
}