<?php
// src/Core/helpers.php

function vite(string ...$entrypoints): string
{
    $html = '';
    $isDev = ($_ENV['APP_ENV'] ?? 'production') === 'development';

    if ($isDev) {
        // MODO DESARROLLO
        $html .= '<script type="module" src="http://localhost:5173/@vite/client"></script>';
        foreach ($entrypoints as $entrypoint) {
            $html .= '<script type="module" src="http://localhost:5173/' . $entrypoint . '"></script>';
        }
    } else {
        // MODO PRODUCCIÓN (VERSIÓN FINAL Y CORREGIDA)
        $manifestPath = dirname(__DIR__, 2) . '/public/build/manifest.json';

        if (!file_exists($manifestPath)) {
            return '';
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $html = '';
        $processedCss = []; // Para evitar duplicados

        foreach ($entrypoints as $entrypoint) {
            if (!isset($manifest[$entrypoint])) continue;

            $entry = $manifest[$entrypoint];
            
            // Determina si el archivo de entrada es JS o CSS por su extensión
            $isJs = str_ends_with(strtolower($entry['file']), '.js');

            if ($isJs) {
                // Si es JS, genera la etiqueta <script>
                $html .= '<script type="module" src="/build/' . $entry['file'] . '"></script>';
            } else {
                // Si no, asumimos que es CSS y generamos la etiqueta <link>
                $html .= '<link rel="stylesheet" href="/build/' . $entry['file'] . '">';
                $processedCss[] = $entry['file']; // Lo marcamos como ya procesado
            }

            // Para las entradas de JS, también debemos incluir el CSS del que dependen
            if ($isJs && !empty($entry['css'])) {
                foreach ($entry['css'] as $cssFile) {
                    if (!in_array($cssFile, $processedCss)) {
                        $html .= '<link rel="stylesheet" href="/build/' . $cssFile . '">';
                        $processedCss[] = $cssFile;
                    }
                }
            }
        }
    }

    return $html;
}