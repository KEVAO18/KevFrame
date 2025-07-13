<?php

namespace App\Http\Interfaces;

use DateTime;

interface TirillasInterface
{
    public function getId(): int;

    public function getPagoId(): PagosInterface;

    public function getContenido(): string;

    public function getFechaGeneracion(): DateTime; // O DateTime
}