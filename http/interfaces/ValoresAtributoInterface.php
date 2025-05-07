<?php

namespace App\Http\Interfaces;

interface ValoresAtributoInterface
{
    public function getId(): int;

    public function getAtributoId(): int;

    public function getValor(): string;
}