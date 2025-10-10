<?php

namespace App\Core;

use App\Core\Cli\Generator;

class Cli
{
    private const VERSION = "0.6.0";

    public function getVersion(){
        return self::VERSION;
    }

    /**
     * El método principal para despachar los comandos.
     * @param array $arguments Los argumentos pasados desde la línea de comandos.
     */
    public function run(array $arguments): void{
        
        $command = array_shift($arguments);

        if ($command === null) {
            $this->help();
            return;
        }
        
        // Se utiliza la convención de que los comandos son métodos
        // make:controller -> makeController
        // serve -> serve
        $methodName = explode(":", $command);

        if (method_exists($this, $methodName[0])) {
            if(count($methodName) > 1){
                $this->{$methodName[0]}($methodName[1], array_shift($arguments), $arguments);
            }else{
                $this->{$methodName[0]}($arguments);
            }
        } else {
            echo "Comando no reconocido: $command\n";
            $this->help();
        }
    }

    /**
     * Muestra la versión y la ayuda de los comandos.
     */
    public function version(): void{
        echo "Kev Framework CLI version " . self::VERSION . "\n";
        $this->help();
    }

    /**
     * Muestra la ayuda de los comandos disponibles.
     */
    public function help(): void{
        echo "Kev Framework CLI\n";
        echo "Desarrollado por KevaoDev\n";
        echo "portfolio https://www.kevao.tech/\n";
        echo "\nAvailable commands:\n";
        echo "  serve               Start development server\n";
        echo "  make:controller     Create a new controller\n";
        echo "  make:model          Create a new model\n";
        echo "  make:handler        Create a new handler\n";
        echo "  make:interface      Create a new interface\n";
        echo "  make:component      Create a new component\n";
        echo "  make:view           Create a new view\n";
        echo "  version             Show the version of the application\n";
        echo "  help                Show this help message\n";
        echo "\nUsage: php kev [command] [name]\n";
    }

    /**
     * Obtiene los argumentos de línea de comandos como un array asociativo.
     * @param array|null $args Los argumentos de línea de comandos.
     * @return array Los argumentos como un array asociativo.
     */
    public function getArgs(?array $args): array{
    
        $argumentos = [];

        foreach ($args as $arg) {
            $argumentos += [explode("=", str_replace("--", "", $arg))[0] => explode("=", str_replace("--", "", $arg))[1]];
        }

        return $argumentos;
        
    }

    /**
     * Inicia el servidor de desarrollo.
     * @param array $args Argumentos opcionales como --port.
     */
    public function serve(?array $args): void {
        $argumentos = $this->getArgs($args); // obtener los argumentos

        $host = $argumentos['host'] ?? "localhost"; // si no hay host, se usa localhost
        $port = $argumentos['port'] ?? 8000; // si no hay port, se usa 8000

        $entryPoint = 'public/runner.php'; // el punto de entrada del servidor
        $localServerUrl = "http://$host:$port"; // la URL del servidor local

        echo "Iniciando servidor en {$localServerUrl}\n"; // muestra la URL del servidor
        echo "Presiona Ctrl+C para detener el servidor.\n"; // muestra el mensaje de detener el servidor

        $this->openBrowser($localServerUrl); // Llama a la función openBrowser para abrir la URL

        passthru("php -S $host:$port $entryPoint"); // Ejecuta el servidor PHP en segundo plano
    }

    /**
     * Crea un nuevo archivo basado en el tipo y nombre.
     * @param string|null $name El nombre del archivo.
     * @param array $args Argumentos del comando.
     */
    public function make(string $type, string $name, ?array $args): void{
        if (!$name || !$type) {
            echo "Debes indicar un tipo y nombre para el archivo (e.g., make:controller User).\n";
            exit(1);
        }

        $name = ucfirst($name);

        if ($type === 'model') {
            $this->makeModel($name);
            return;
        }

        $directories = [
            'controller' => dirname(__DIR__) . "/Http/Controllers/", // Sube a src/ y baja a Http/Controllers
            'handler'    => dirname(__DIR__) . "/Http/Handlers/",
            'interface'  => dirname(__DIR__) . "/Http/Interfaces/",
            'component'  => dirname(__DIR__, 2) . "/web/componentes/", // Sube a la raíz y baja a web/
            'view'       => dirname(__DIR__, 2) . "/web/views/",
        ];

        if (!isset($directories[$type])) {
            echo "Tipo de archivo no válido: $type\n";
            exit(1);
        }

        // 2. Usamos el Generator para obtener el contenido
        $content = Generator::get($type, ['name' => $name]);
        
        $path = $directories[$type] . $name . ucfirst($type) . ".php";
        
        if ($type === 'view') { // Las vistas no llevan extensión .php en el nombre
            $path = $directories[$type] . $name . ".php";
        }

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $content);
        echo ucfirst($type) . " creado en {$path}\n";
    }

        /**
     * Genera un nuevo archivo de modelo.
     */
    private function makeModel(string $name): void{
        $tableName = strtolower($name) . 's';
        $fields = [];
        $primaryKey = 'id';

        try {
            $db = \App\Core\Database::getInstance();
            $stmt = $db->query("DESCRIBE {$tableName}");
            $schema = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($schema as $column) {
                $fields[$column['Field']] = $column['Type'];
                if ($column['Key'] === 'PRI') {
                    $primaryKey = $column['Field'];
                }
            }
        } catch (\PDOException $e) {
            echo "Advertencia: No se pudo conectar o la tabla '{$tableName}' no existe. Se creará un modelo vacío.\n";
        }

        $fieldsString = '';
        foreach ($fields as $field => $type) {
            $fieldsString .= "        '{$field}' => '{$type}',\n";
        }
        
        // 3. Usamos el Generator con todas las variables necesarias
        $content = Generator::get('model', [
            'name'       => $name,
            'tableName'  => $tableName,
            'primaryKey' => $primaryKey,
            'fields'     => rtrim($fieldsString) // Quitamos el último salto de línea
        ]);
        
        $path = __DIR__ . "/../models/" . $name . "Model.php";
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $content);
        echo "Modelo creado en {$path}\n";
    }

    /**
     * Intenta abrir una URL en el navegador por defecto del sistema.
     * @param string $url La URL a abrir.
     */
    private function openBrowser(string $url): void{
        $escapedUrl = escapeshellarg($url);
        $command = '';

        if (PHP_OS_FAMILY === 'Windows') {
            $command = "start \"\" {$escapedUrl}";
        } elseif (PHP_OS_FAMILY === 'Darwin') { // macOS
            $command = "open {$escapedUrl}";
        } else { // Linux
            $command = "xdg-open {$escapedUrl}";
        }
        
        // Ejecuta el comando en segundo plano sin mostrar la salida
        passthru($command);
        echo "Abriendo el navegador automáticamente. Si no se abre, navega a la URL manualmente.\n";
    }
}
