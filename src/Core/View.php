<?php

namespace App\Core;

use App\Templates\KevTemplateEngine;

/**
 * Clase View mejorada para prevenir Path Traversal.
 */
class View
{
    public static function render(string $viewPath, array $data = []): void
    {
        $viewFile = self::resolveSecurePath($viewPath, 'componentes');

        extract($data);

        ob_start();
        $compilationResult = KevTemplateEngine::compile($viewFile);
        
        if (is_file($compilationResult) && file_exists($compilationResult)) {
            include $compilationResult;
        } else {
            eval('?>' . $compilationResult);
        }

        $viewOutput = ob_get_clean();

        $layoutPath = KevTemplateEngine::getLayout();

        if ($layoutPath) {
            $layoutFile = self::resolveSecurePath($layoutPath, 'views');
            
            $compiledLayoutResult = KevTemplateEngine::compile($layoutFile);
            KevTemplateEngine::setSection('content', $viewOutput);

            if (is_file($compiledLayoutResult) && file_exists($compiledLayoutResult)) {
                include $compiledLayoutResult;
            } else {
                eval('?>' . $compiledLayoutResult);
            }
        } else {
            echo $viewOutput;
        }

        KevTemplateEngine::clearSections();
        KevTemplateEngine::setLayout('');
    }

    /**
     * Resuelve y valida una ruta de vista de forma segura para prevenir Path Traversal.
     *
     * @param string $path La ruta relativa de la vista (ej. 'main/home').
     * @param string $type El tipo de vista ('componentes' o 'views').
     * @return string La ruta absoluta y segura al archivo.
     * @throws \Exception Si la ruta es inválida o insegura.
     */
    private static function resolveSecurePath(string $path, string $type): string
    {
        // 1. Define el directorio base permitido.
        $baseDir = realpath(__DIR__ . "/../../web/{$type}");
        if (!$baseDir) {
            throw new \Exception("El directorio base '{$type}' no existe.");
        }

        // 2. Sanea la ruta de entrada para eliminar caracteres peligrosos.
        // Previene ataques como '..', './', etc.
        $sanitizedPath = str_replace(['..', './'], '', $path);
        
        // 3. Construye la ruta completa y determina la extensión del archivo.
        $extension = ($type === 'componentes') ? 'Component.php' : '.php';
        $parts = explode('/', $sanitizedPath);
        $lastPart = ucfirst(array_pop($parts));
        $filePath = $baseDir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts) . DIRECTORY_SEPARATOR . $lastPart . $extension;

        // 4. Obtiene la ruta canónica (absoluta y sin '..').
        $realFilePath = realpath($filePath);

        // 5. ¡La comprobación de seguridad crucial!
        // Asegura que la ruta real del archivo comience con la ruta del directorio base.
        // Esto "enjaula" el acceso y previene que se salga del directorio permitido.
        if ($realFilePath === false || strpos($realFilePath, $baseDir) !== 0) {
            throw new \Exception("Acceso denegado o vista no encontrada: '{$path}'");
        }

        return $realFilePath;
    }
}