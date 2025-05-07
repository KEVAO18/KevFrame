<?php

namespace App\Http\Interfaces;

interface TirillasInterface
{
    public function getId(): int;

    public function getPagoId(): int;

    public function getContenido(): string;

    public function getFechaGeneracion(): string; // O DateTime
}