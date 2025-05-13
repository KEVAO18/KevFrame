<?php

namespace App\Http\Interfaces;

interface EstadosProductoInterface{
    public function getId(): int;
    public function getNombre(): string;
}