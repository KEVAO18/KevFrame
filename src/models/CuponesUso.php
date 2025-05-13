<?php

namespace App\Models;

use App\Http\Interfaces\CuponesUsoInterface;
use DateTime;

class CuponesUso implements CuponesUsoInterface{

    private int $id;
    private int $cupon;
    private int $usuario;
    private DateTime $fechaUso;

    public function __construct(int $id, int $cupon, int $usuario, DateTime $fechaUso) {
        $this->id = $id;
        $this->cupon = $cupon;
        $this->usuario = $usuario;
        $this->fechaUso = $fechaUso;   
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getCupon(): int {
        return $this->cupon; 
    }

    public function getUsuario(): int {
        return $this->usuario;
    }

    public function getFechaUso(): DateTime {
        return $this->fechaUso;
    }

}