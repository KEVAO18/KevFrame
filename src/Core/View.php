<?php

namespace App\Core;

use App\Templates\KevTemplateEngine;

/**
 * Clase View corregida para manejar compilación con y sin caché.
 */
class View
{
    public static function render(string $viewPath, array $data = []): void
    {
        // 1. Obtener la ruta del archivo de la vista original.
        $viewFile = self::getViewFile($viewPath);

        // 2. Compilar la vista. El resultado puede ser una ruta (cache ON) o un string (cache OFF).
        $compilationResult = KevTemplateEngine::compile($viewFile);

        // 3. Extraer los datos para que estén disponibles en las vistas.
        extract($data);

        // 4. Capturar el output de la vista ejecutada.
        ob_start();

        // >>> INICIO DE LA CORRECCIÓN <<<
        // Comprobamos si el resultado es una ruta a un archivo que existe.
        if (is_file($compilationResult) && file_exists($compilationResult)) {
            // Si es un archivo (caché activado), lo incluimos.
            include $compilationResult;
        } else {
            // Si no, es el contenido compilado (caché desactivado), lo ejecutamos con eval.
            eval('?>' . $compilationResult);
        }

        $viewOutput = ob_get_clean();

        // 5. Verificar si la vista definió un layout con @extends.
        $layoutPath = KevTemplateEngine::getLayout();

        if ($layoutPath) {
            $layoutFile = self::getLayoutFile($layoutPath);
            // Compilamos el layout.
            $compiledLayoutResult = KevTemplateEngine::compile($layoutFile);

            // Definimos la sección de contenido principal.
            KevTemplateEngine::setSection('content', $viewOutput);

            // Incluimos el layout compilado (manejando también ambos casos).
            if (is_file($compiledLayoutResult) && file_exists($compiledLayoutResult)) {
                include $compiledLayoutResult;
            } else {
                eval('?>' . $compiledLayoutResult);
            }
        } else {
            // 7. Si no hay layout, mostramos el contenido de la vista.
            echo $viewOutput;
        }

        KevTemplateEngine::clearSections();
        KevTemplateEngine::setLayout('');
    }

    protected static function getViewFile(string $view): string
    {
        // Directorio base para los componentes
        $basePath = realpath(__DIR__ . '/../../web/componentes');

        if (!preg_match('/^[a-zA-Z0-9\-\/]+$/', $view)) {
            throw new \Exception("$view es un nombre de vista inválido.");
        }

        // Convertir el nombre de la vista a un nombre de archivo
        $parts = explode('/', $view);
        $lastPart = array_pop($parts);
        $ucfirstPart = ucfirst($lastPart);

        $path = implode(DIRECTORY_SEPARATOR, $parts);

        // Formatear el nombre del archivo: Ucfirst + "Component.php"
        $file = realpath($basePath . DIRECTORY_SEPARATOR . $path . DIRECTORY_SEPARATOR . $ucfirstPart . 'Component.php');

        if ($file === false || strpos($file, $basePath) !== 0) {
            throw new \Exception("Vista '$view' no encontrada o acceso denegado en: " . $basePath . '/' . $view . '.php');
        }

        return $file;
    }

    protected static function getLayoutFile(string $layout): string
    {
        $basePath = realpath(__DIR__ . '/../../web/views');

        if (!preg_match('/^[a-zA-Z0-9\-\/]+$/', $layout)) {
            throw new \Exception("$layout es un nombre de layout inválido.");
        }

        $file = realpath($basePath . '/' . str_replace('/', DIRECTORY_SEPARATOR, $layout) . '.php');

        if ($file === false || strpos($file, $basePath) !== 0) {
            throw new \Exception("Layout '$layout' no encontrado o acceso denegado en: " . $basePath . '/' . $layout . '.php');
        }

        return $file;
    }
}
