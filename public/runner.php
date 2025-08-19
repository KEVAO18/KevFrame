<?php
// router.php
if (php_sapi_name() === 'cli-server') {
    $path = __DIR__ . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    if ($path !== __DIR__.'/' && file_exists($path)) {
        return false; // Deja servir archivos estáticos normalmente
    }
}

require __DIR__ . '/../serve.php';
