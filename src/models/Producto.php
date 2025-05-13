<?php

namespace App\Models;

use App\Http\Interfaces\ProductosInterface;

class Producto implements ProductosInterface
{
    private int $id;
    private string $nombre;
    private string $descripcion;
    private int $unidades;
    private float $precio;
    private int $estado_id;

    public function __construct() {}

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getUnidades(): int {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): void {
        $this->unidades = $unidades;
    }

    public function getPrecio(): float {
        return $this->precio;
    }

    public function setPrecio(float $precio): void {
        $this->precio = $precio;
    }

    public function getEstadoId(): int {
        return $this->estado_id;
    }

    public function setEstadoId(int $estado_id): void {
        $this->estado_id = $estado_id;
    }
}