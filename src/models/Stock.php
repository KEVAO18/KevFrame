<?php

namespace App\Models;

use App\Http\Interfaces\StockInterface;

class Stock implements StockInterface{
    private int $id;
    private int $producto;
    private int $agotado;

    public function __construct(int $id, int $producto, int $agotado) {
        $this->id = $id;
        $this->producto = $producto;
        $this->agotado = $agotado;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getProducto():?int{
        return $this->producto;
    }

    public function getAgotado():?int{
        return $this->agotado;
    }

}