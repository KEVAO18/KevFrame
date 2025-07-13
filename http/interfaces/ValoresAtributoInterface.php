<?php

namespace App\Http\Interfaces;

interface ValoresAtributoInterface
{
    public function getId(): int;

    public function getAtributoId(): AtributosInterface;

    public function getValor(): string;
}