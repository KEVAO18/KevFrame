<?php

namespace App\Models;

use App\Http\Interfaces\CredencialesInterface;

class Credenciales implements CredencialesInterface{
    private int $id;
    private Usuario $usuario;
    private TipoCredencial $tipo;

    public function __construct(int $id, Usuario $usuario, TipoCredencial $tipo) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->tipo = $tipo;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function getTipo(): TipoCredencial {
        return $this->tipo;
    }
}