<?php

namespace App\Templates;

/**
 * KevEngine - Motor de directivas personalizado para KevFrame
 * Sintaxis limpia y eficiente inspirada en las mejores prácticas
 */

class KevEngine implements TemplateEngineInterface
{
    protected array $customDirectives = [];

    public function compile(string $content, array $data = []): string
    {
        // Procesar directivas en orden de precedencia
        $content = $this->processSections($content);
        $content = $this->processExtends($content);
        $content = $this->processYields($content);
        $content = $this->processLoops($content);
        $content = $this->processConditionals($content);
        $content = $this->processSwitch($content);
        $content = $this->processVariables($content);
        $content = $this->processCustomDirectives($content);

        return $content;
    }

    public function registerDirective(string $name, callable $handler): void
    {
        $this->customDirectives[$name] = $handler;
    }

    /**
     * Procesa secciones @section('name')...@endsection
     */
    protected function processSections(string $content): string
    {
        return preg_replace_callback(
            '/@section\(["\']([^"\']+)["\']\)(.*?)@endsection/s',
            function ($matches) {
                $sectionName = $matches[1];
                $sectionContent = $matches[2];
                KevTemplateEngine::setSection($sectionName, $sectionContent);
                return $sectionContent;
            },
            $content
        );
    }

    /**
     * Procesa la directiva @extends('layout.name')
     */
    protected function processExtends(string $content): string
    {
        return preg_replace_callback(
            '/@extends\(["\']([^"\']+)["\']\)/',
            function ($matches) {
                $layoutName = $matches[1];
                KevTemplateEngine::setLayout($layoutName);
                return ''; // La directiva @extends se elimina de la plantilla hija
            },
            $content
        );
    }

    /**
     * Procesa la directiva @yield('name')
     */
    protected function processYields(string $content): string
    {
        return preg_replace_callback(
            '/@yield\(["\']([^"\']+)["\']\)/',
            function ($matches) {
                $sectionName = $matches[1];
                // Compila a código PHP que obtiene la sección del motor
                return '<?php echo App\Templates\KevTemplateEngine::getSection("' . $sectionName . '"); ?>';
            },
            $content
        );
    }

    /**
     * Procesa bucles @foreach($items as $item)...@endforeach
     */
    protected function processLoops(string $content): string
    {
        // @foreach($items as $item)
        $content = preg_replace(
            '/@foreach\s*\(\s*(.+?)\s+as\s+(.+?)\s*\)(.*?)@endforeach/s',
            '<?php foreach($1 as $2): ?>$3<?php endforeach; ?>',
            $content
        );

        // @foreach($items as $key => $item)
        $content = preg_replace(
            '/@foreach\s*\(\s*(.+?)\s+as\s+(.+?)\s*=>\s*(.+?)\s*\)(.*?)@endforeach/s',
            '<?php foreach($1 as $2 => $3): ?>$4<?php endforeach; ?>',
            $content
        );

        return $content;
    }

    /**
     * Procesa condicionales @if($condition)...@endif
     */
    protected function processConditionals(string $content): string
    {
        // @if($condition)
        $content = preg_replace('/@if\s*\(\s*(.+?)\s*\)/', '<?php if($1): ?>', $content);

        // @elif($condition)
        $content = preg_replace('/@elif\s*\(\s*(.+?)\s*\)/', '<?php elseif($1): ?>', $content);

        // @else
        $content = preg_replace('/@else/', '<?php else: ?>', $content);

        // @endif
        $content = preg_replace('/@endif/', '<?php endif; ?>', $content);

        return $content;
    }

    /**
     * Procesa switch @switch($variable)...@endswitch
     */
    protected function processSwitch(string $content): string
    {
        // @switch($variable)
        $content = preg_replace('/@switch\s*\(\s*(.+?)\s*\)/', '<?php switch($1): ?>', $content);

        // @case($value)
        $content = preg_replace('/@case\s*\(\s*(.+?)\s*\)/', '<?php case $1: ?>', $content);

        // @break
        $content = preg_replace('/@break/', '<?php break; ?>', $content);

        // @default
        $content = preg_replace('/@default/', '<?php default: ?>', $content);

        // @endswitch
        $content = preg_replace('/@endswitch/', '<?php endswitch; ?>', $content);

        return $content;
    }

    /**
     * 
     * Procesa variables {{ $variable }} y la directiva @raw($variable)
     */
    protected function processVariables(string $content): string
    {
        // Variables escapadas {{ $variable }} (Seguro por defecto)
        $content = preg_replace(
            '/\{\{\s*(.+?)\s*\}\}/',
            '<?php echo htmlspecialchars($1 ?? "", ENT_QUOTES, "UTF-8"); ?>',
            $content
        );

        // NUEVA DIRECTIVA: @raw($variable) para datos sin escapar
        $content = preg_replace(
            '/@raw\s*\(\s*(.+?)\s*\)/',
            '<?php echo $1 ?? ""; ?>',
            $content
        );

        return $content;
    }

    /**
     * Procesa directivas personalizadas @customDirective()
     * 
     * @return string
     */
    protected function processCustomDirectives(string $content): string
    {
        foreach ($this->customDirectives as $name => $handler) {
            $pattern = '/@' . $name . '\s*\(([^)]*)\)/';
            $content = preg_replace_callback($pattern, function ($matches) use ($handler) {
                $params = isset($matches[1]) ? $matches[1] : '';
                return $handler($params);
            }, $content);
        }
        return $content;
    }
}
