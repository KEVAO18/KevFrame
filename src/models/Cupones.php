<?php

namespace App\Models;

use App\Http\Interfaces\CuponesInterface;
use DateTime;

class Cupones implements CuponesInterface{

    private int $id;
    private string $codigo;
    private float $descuento;
    private string $tipo;
    private DateTime $validoDesde;
    private DateTime $validoHasta;
    private int $limiteUso;

    public function __construct(int $id, string $codigo, float $descuento, string $tipo, DateTime $validoDesde, DateTime $validoHasta, int $limiteUso) {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->descuento = $descuento;
        $this->tipo = $tipo;
        $this->validoDesde = $validoDesde;
        $this->validoHasta = $validoHasta;
        $this->limiteUso = $limiteUso;
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getCodigo(): string {
        return $this->codigo; 
    }

    public function getDescuento(): float {
        return $this->descuento;
    }

    public function getTipo(): string {
        return $this->tipo;
    }

    public function getValidoDesde(): DateTime {
        return $this->validoDesde;
    }

    public function getValidoHasta(): DateTime {
        return $this->validoHasta;
    }

    public function getLimiteUso(): int {
        return $this->limiteUso;
    }
}