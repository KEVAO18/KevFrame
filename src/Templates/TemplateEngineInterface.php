<?php

namespace App\Templates;

/**
 * Interface para motores de directivas personalizados
 */
interface TemplateEngineInterface
{
    public function compile(string $content, array $data = []): string;
    public function registerDirective(string $name, callable $handler): void;
}