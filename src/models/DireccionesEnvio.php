<?php

namespace App\Models;

use App\Http\Interfaces\DireccionesEnvioInterface;

class DireccionesEnvio implements DireccionesEnvioInterface {
    private int $id;
    private Usuario $usuario;
    private string $direccion;
    private ?string $ciudad;
    private ?string $departamento;
    private ?string $pais;
    private ?bool $principal;

    public function __construct(int $id, Usuario $usuario, string $direccion, ?string $ciudad, ?string $departamento, ?string $pais, ?bool $principal) {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->direccion = $direccion;
        $this->ciudad = $ciudad;
        $this->departamento = $departamento;
        $this->pais = $pais;
        $this->principal = $principal;   
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function getDireccion(): string {
        return $this->direccion;
    }

    public function getCiudad():?string {
        return $this->ciudad;
    }

    public function getDepartamento():?string {
        return $this->departamento;
    }

    public function getPais():?string {
        return $this->pais;
    }

    public function getPrincipal():?bool {
        return $this->principal; 
    }
}