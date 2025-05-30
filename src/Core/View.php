<?php

namespace App\Core;

class View
{
    protected static array $sections = [];
    protected static string $layout;

    public static function render(string $viewPath): void
    {
        // Reiniciar
        self::$sections = [];
        self::$layout = '';

        // Cargar vista 
        ob_start();
        include self::getViewFile($viewPath);
        ob_get_clean();

        // Renderizar plantilla principal
        // Renderizar plantilla principal
        ob_start();
        include self::getViewFile(self::$layout);
        $content = ob_get_clean();

        // Reemplazar los @yield por secciones
        foreach (self::$sections as $name => $sectionContent) {
            $pattern = '/@yield\(["\']' . preg_quote($name, '/') . '["\']\)/';
            $content = preg_replace($pattern, $sectionContent, $content);
        }

        echo $content;
    }

    public static function import(string $layout): void
    {

        // sanitizar nombre de layout
        if (!preg_match('/^[a-zA-Z0-9_\-\/]+$/', $layout)) {
            throw new \Exception("$layout es un nombre de layout inválido.");
        }

        self::$layout = $layout;
    }

    public static function section(string $name, callable $contentCallback): void
    {
        ob_start();
        $contentCallback();
        self::$sections[$name] = ob_get_clean();
    }

    protected static function getViewFile(string $view): string
    {
        $basePath = realpath(__DIR__ . '/../../web'); // Subir dos niveles desde src/Core a la raíz y luego a web
        if ($basePath === false) {
            throw new \Exception("Ruta base de vistas no encontrada.");
        }

        // Sanitizar nombre de vista para evitar problemas
        if (!preg_match('/^[a-zA-Z0-9_\-\/]+$/', $view)) {
            throw new \Exception("$view es un nombre de vista inválido.");
        }

        $file = realpath($basePath . '/' . $view . '.php');

        if ($file === false || strpos($file, $basePath) !== 0) {
            throw new \Exception("Vista '$view' no encontrada o acceso denegado.");
        }

        return $file;
    }
}
