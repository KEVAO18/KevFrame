<?php

namespace App\Models;

use App\Http\Interfaces\CuponesUsoInterface;
use DateTime;

class CuponesUso implements CuponesUsoInterface{

    private int $id;
    private Cupones $cupon;
    private Usuario $usuario;
    private DateTime $fechaUso;

    public function __construct(int $id, Cupones $cupon, Usuario $usuario, DateTime $fechaUso) {
        $this->id = $id;
        $this->cupon = $cupon;
        $this->usuario = $usuario;
        $this->fechaUso = $fechaUso;   
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getCupon(): Cupones {
        return $this->cupon; 
    }

    public function getUsuario(): Usuario {
        return $this->usuario;
    }

    public function getFechaUso(): DateTime {
        return $this->fechaUso;
    }

}