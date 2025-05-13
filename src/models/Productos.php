<?php

namespace App\Models;

use App\Http\Interfaces\ProductosInterface;

class Productos implements ProductosInterface{
    private int $id;
    private string $nombre;
    private ?string $descripcion;
    private int $unidades;
    private float $precio;
    private int $estadoId;

    public function __construct(int $id, string $nombre, ?string $descripcion, int $unidades, float $precio, int $estadoId) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->unidades = $unidades;
        $this->precio = $precio;
        $this->estadoId = $estadoId;
    }

    public function getId(): int{
        return $this->id; 
    }

    public function getNombre(): string{
        return $this->nombre;
    }

    public function getDescripcion():?string{
        return $this->descripcion; 
    }

    public function getUnidades(): int{
        return $this->unidades; 
    }

    public function getPrecio(): float{
        return $this->precio;
    }

    public function getEstadoId(): int{
        return $this->estadoId;
    }
}