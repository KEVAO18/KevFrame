<?php

namespace App\Core;

/**
 * Clase View para cargar y renderizar plantillas con un sistema de layouts y callbacks para secciones.
 */
class View
{
    protected static array $sections = [];
    protected static string $layout = '';
    protected static array $viewData = [];

    /**
     * Define el layout principal para la vista.
     *
     * @param string $layoutPath
     */
    public static function layout(string $layoutPath): void
    {
        self::$layout = $layoutPath;
    }

    /**
     * Renderiza una vista y su layout, si existe.
     *
     * @param string $viewPath La ruta de la vista (ej: 'home/index').
     * @param array $data Los datos que se pasarán a la vista.
     */
    public static function render(string $viewPath, array $data = []): void
    {
        // Almacena los datos de la vista para que el callback los pueda usar.
        self::$viewData = $data;

        // 1. Obtener la ruta completa del archivo de vista.
        $viewFile = self::getViewFile($viewPath);

        // 2. Extraer los datos en variables locales para la vista.
        extract($data);

        // 3. Capturar el contenido de la vista en un buffer.
        ob_start();
        include $viewFile;
        $viewContent = ob_get_clean();

        // 4. Si hay un layout definido, renderizarlo.
        if (self::$layout) {
            $layoutFile = self::getLayoutFile(self::$layout);
            
            // 5. Incluir el contenido de la vista en el buffer del layout.
            // Para que 'yield' funcione, el contenido debe estar en una variable.
            $__contentForLayout = $viewContent;

            // 6. Capturar el contenido del layout.
            ob_start();
            include $layoutFile;
            $finalContent = ob_get_clean();

            // 7. Reemplazar los marcadores @yield por el contenido de las secciones.
            foreach (self::$sections as $name => $sectionContent) {
                $pattern = '/@yield\(["\']' . preg_quote($name, '/') . '["\']\)/';
                $finalContent = preg_replace($pattern, $sectionContent, $finalContent);
            }

            // 8. Imprimir el contenido final.
            echo $finalContent;
        } else {
            // 9. Si no hay layout, imprimir el contenido de la vista directamente.
            echo $viewContent;
        }

        // Restablecer los datos después de la renderización.
        self::$viewData = [];
        self::$sections = [];
    }
    
    /**
     * Inicia la captura de contenido para una sección usando un callback.
     *
     * @param string $name
     * @param callable $contentCallback
     */
    public static function section(string $name, callable $contentCallback): void
    {
        ob_start();
        $contentCallback(self::$viewData);
        self::$sections[$name] = ob_get_clean();
    }

    /**
     * Muestra el contenido de una sección o un valor por defecto.
     *
     * @param string $name
     */
    public static function yield(string $name): void
    {
        echo self::$sections[$name] ?? '';
    }

    /**
     * Obtiene la ruta absoluta de un archivo de vista.
     *
     * @param string $view
     * @return string
     * @throws \Exception
     */
    protected static function getViewFile(string $view): string
    {
        // Base path is 'web'
        $basePath = realpath(__DIR__ . '/../../web');
        
        // Sanitization to prevent directory traversal attacks.
        if (!preg_match('/^[a-zA-Z0-9\-\/]+$/', $view)) {
            throw new \Exception("$view es un nombre de vista inválido.");
        }
        
        $file = realpath($basePath . '/' . $view . '.php');

        // Double check to ensure the file exists and is within the base directory.
        if ($file === false || strpos($file, $basePath) !== 0) {
            throw new \Exception("Vista '$view' no encontrada o acceso denegado.");
        }

        return $file;
    }

    /**
     * Obtiene la ruta absoluta de un archivo de layout.
     *
     * @param string $layout
     * @return string
     * @throws \Exception
     */
    protected static function getLayoutFile(string $layout): string
    {
        // Base path for layouts is 'web/plantillas'.
        $basePath = realpath(__DIR__ . '/../../web/views');

        // Sanitization to prevent directory traversal attacks.
        if (!preg_match('/^[a-zA-Z0-9\-\/]+$/', $layout)) {
            throw new \Exception("$layout es un nombre de layout inválido.");
        }

        $file = realpath($basePath . '/' . $layout . '.php');

        // Double check to ensure the file exists and is within the base directory.
        if ($file === false || strpos($file, $basePath) !== 0) {
            throw new \Exception("Layout '$layout' no encontrado o acceso denegado.");
        }

        return $file;
    }
}
