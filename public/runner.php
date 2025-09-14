<?php
// runner.php
if (php_sapi_name() === 'cli-server') {
    $requestUri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    $filePath = __DIR__ . $requestUri;
    
    // Lista de extensiones de archivos estáticos
    $staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot'];
    $fileExtension = strtolower(pathinfo($requestUri, PATHINFO_EXTENSION));
    
    // Si es un archivo estático y existe, servirlo directamente SIN pasar por el router
    if (in_array($fileExtension, $staticExtensions)) {
        if (file_exists($filePath) && is_file($filePath)) {
            // Establecer el Content-Type correcto
            $contentTypes = [
                'css' => 'text/css',
                'js' => 'application/javascript',
                'png' => 'image/png',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                'ico' => 'image/x-icon',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                'ttf' => 'font/ttf'
            ];
            
            if (isset($contentTypes[$fileExtension])) {
                header('Content-Type: ' . $contentTypes[$fileExtension]);
            }
            
            // Servir el archivo y terminar la ejecución
            readfile($filePath);
            exit;
        } else {
            // Archivo estático no encontrado
            http_response_code(404);
            echo "File not found: " . $requestUri;
            exit;
        }
    }
}

// Si no es un archivo estático, pasar al sistema de routing normal
require __DIR__ . '/../serve.php';
?>