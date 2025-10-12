<?php

namespace App\Templates;

/**
 * Clase principal del motor de templates KevFrame
 */
class KevTemplateEngine
{
    protected static TemplateEngineInterface $engine;
    protected static array $sections = [];
    protected static string $layout = '';
    protected static bool $cacheEnabled = false;
    protected static string $cacheDir = '';

    public static function setEngine(TemplateEngineInterface $engine): void
    {
        self::$engine = $engine;
    }

    public static function getEngine(): TemplateEngineInterface
    {
        if (!isset(self::$engine)) {
            self::$engine = new KevEngine(); // Tu motor por defecto
        }
        return self::$engine;
    }

    public static function enableCache(string $cacheDir): void
    {
        self::$cacheEnabled = true;
        self::$cacheDir = $cacheDir;

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }
    }

    public static function compile(string $templatePath, array $data = []): string
    {
        $content = file_get_contents($templatePath);

        // Verificar cache
        if (self::$cacheEnabled) {
            $cacheKey = md5($templatePath . filemtime($templatePath));
            $cacheFile = self::$cacheDir . '/' . $cacheKey . '.php';

            if (file_exists($cacheFile)) {
                return $cacheFile;
            }
        }

        // Compilar con tu motor
        $compiledContent = self::getEngine()->compile($content, $data);

        // Guardar en cache
        if (self::$cacheEnabled) {
            file_put_contents($cacheFile, $compiledContent);
            return $cacheFile;
        }

        return $compiledContent;
    }

    // Métodos de secciones
    public static function setSection(string $name, string $content): void
    {
        self::$sections[$name] = $content;
    }

    public static function getSection(string $name): string
    {
        return self::$sections[$name] ?? '';
    }

    public static function getSections(): array
    {
        return self::$sections;
    }

    public static function clearSections(): void
    {
        self::$sections = [];
    }

    public static function setLayout(string $layout): void
    {
        self::$layout = $layout;
    }

    public static function getLayout(): string
    {
        return self::$layout;
    }

    // Directivas personalizadas comunes
    public static function registerCommonDirectives(): void
    {
        $engine = self::getEngine();

        // @csrf
        $engine->registerDirective('csrf', function () {
            return '<?php echo "<input type=\"hidden\" name=\"_token\" value=\"" . \App\Security\CSRF::generateToken() . "\">"; ?>';
        });

        // @money($amount)
        $engine->registerDirective('money', function ($amount) {
            return "<?php echo '$' . number_format($amount, 2); ?>";
        });

        // @date($timestamp)
        $engine->registerDirective('date', function ($params) {
            return "<?php echo date('Y-m-d', $params); ?>";
        });

        $engine->registerDirective('vite', function ($params) {
            // $params contendrá los argumentos de la directiva, ej: "'resources/css/app.css', 'resources/js/app.js'"
            // Simplemente lo pasamos a nuestra función helper vite()
            return "<?php echo vite({$params}); ?>";
        });
    }
}
