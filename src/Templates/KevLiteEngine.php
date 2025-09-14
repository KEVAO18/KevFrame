<?php

namespace App\Templates;

/**
 * Motor alternativo con sintaxis minimalista
 */
class KevLiteEngine implements TemplateEngineInterface
{
    protected array $customDirectives = [];

    public function compile(string $content, array $data = []): string
    {
        // Sintaxis ultra simple
        // Variables: {var}
        $content = preg_replace(
            '/\{([a-zA-Z_][a-zA-Z0-9_\[\]\'\"]*)\}/', 
            '<?php echo htmlspecialchars($1 ?? "", ENT_QUOTES, "UTF-8"); ?>', 
            $content
        );

        // Loops: {for items as item} ... {/for}
        $content = preg_replace(
            '/\{for\s+(.+?)\s+as\s+(.+?)\}(.*?)\{\/for\}/s',
            '<?php foreach($1 as $2): ?>$3<?php endforeach; ?>',
            $content
        );

        // Condicionales: {if condition} ... {/if}
        $content = preg_replace('/\{if\s+(.+?)\}/', '<?php if($1): ?>', $content);
        $content = preg_replace('/\{else\}/', '<?php else: ?>', $content);
        $content = preg_replace('/\{\/if\}/', '<?php endif; ?>', $content);

        return $content;
    }

    public function registerDirective(string $name, callable $handler): void
    {
        $this->customDirectives[$name] = $handler;
    }
}