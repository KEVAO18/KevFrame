<?php

namespace App\Models;

use App\Http\Interfaces\ValoresAtributoInterface;

class ValoresAtributo implements ValoresAtributoInterface
{
    
        private int $id;
        private int $atributo_id;
        private string $valor;

    public function __construct(int $id, int $atributo_id, string $valor){
        $this->id = $id;
        $this->atributo_id = $atributo_id;
        $this->valor = $valor;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getAtributoId(): int {
        return $this->atributo_id;
    }

    public function getValor(): string {
        return $this->valor;
    }
}