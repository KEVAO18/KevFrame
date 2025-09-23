<?php

namespace App\Core\Cli;

class Generator
{
    /**
     * Obtiene el contenido de una plantilla y reemplaza sus variables.
     *
     * @param string $stubName El nombre del archivo de la plantilla (sin .stub).
     * @param array $replacements Un array asociativo de [variable => valor].
     * @return string El contenido de la plantilla procesada.
     */
    public static function get(string $stubName, array $replacements): string
    {
        $stubPath = __DIR__ . "/Stubs/{$stubName}.stub";

        if (!file_exists($stubPath)) {
            throw new \Exception("La plantilla '{$stubName}.stub' no existe.");
        }

        $content = file_get_contents($stubPath);

        foreach ($replacements as $variable => $value) {
            $placeholder = "{{{$variable}}}";
            $content = str_replace($placeholder, $value, $content);
        }

        return $content;
    }
}