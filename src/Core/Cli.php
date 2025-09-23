<?php

namespace App\Core;

class Cli
{
    private const VERSION = "0.1.3";

    /**
     * El método principal para despachar los comandos.
     * @param array $arguments Los argumentos pasados desde la línea de comandos.
     */
    public function run(array $arguments): void
    {
        
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
    public function version(): void
    {
        echo "Kev Framework CLI version " . self::VERSION . "\n";
        $this->help();
    }

    /**
     * Muestra la ayuda de los comandos disponibles.
     */
    public function help(): void
    {
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
    public function make(string $type, string $name, ?array $args): void
    {
        $argumentos = $this->getArgs($args); // obtener los argumentos

        if (!$name || !$type) {
            echo "Debes indicar un tipo y nombre para el archivo (e.g., make:controller User).\n";
            exit(1);
        }

        $name = ucfirst($name); // convierte el nombre a mayúsculas

        $directories = [
            'controller' => __DIR__ . "/../../http/controllers/",
            'model' => __DIR__ . "/../models/",
            'handler' => __DIR__ . "/../../http/handlers/",
            'interface' => __DIR__ . "/../../http/interfaces/",
            'component' => __DIR__ . "/../../web/componentes/",
            'view' => __DIR__. "/../../web/views/",

        ];

        $templates = [
            'controller' => <<<EOT
            <?php

            namespace App\Http\Controllers;
            
            class {$name}Controller {
                
                public function index(){
                    echo "Controller {$name} it's working";
                }
                
            }
            ?>
            EOT,
            'model' => function($name){

                // Adivinar el nombre de la tabla (ej. User -> users)
                $tableName = strtolower($name) . 's';
        
                $fields = [];
                $primaryKey = 'id';

                try {
                    // Conectarse a la base de datos
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
                    echo "Advertencia: No se pudo conectar a la base de datos o la tabla '{$tableName}' no existe.\n";
                    echo "Se creará un modelo vacío.\n";
                    // No hacer nada, se creará un modelo sin campos
                }

                // Formatear los campos para la plantilla
                $fieldsString = '';
                foreach ($fields as $field => $type) {
                    $fieldsString .= "        '{$field}' => '{$type}',\n";
                }

                // 4. Definir la plantilla del modelo con la información obtenida
                $template = <<<EOT
                <?php

                namespace App\Models;

                class {$name}Model extends Model
                {
                    /**
                     * El nombre de la tabla en la base de datos.
                     */
                    protected string \$table = '{$tableName}';

                    /**
                     * La clave primaria de la tabla.
                     */
                    protected string \$primaryKey = '{$primaryKey}';

                    /**
                     * El esquema de la tabla (descubierto automáticamente).
                     */
                    protected array \$fields = [
                {$fieldsString}    ];

                    /**
                     * Define las relaciones del modelo aquí.
                     * Ejemplo:
                     * protected array \$relations = [
                     * 'posts' => ['hasMany', PostModel::class, 'user_id']
                     * ];
                     */
                    protected array \$relations = [];
                }
                ?>
                EOT;

                 // 5. Crear el archivo
                $path = __DIR__ . "/../models/" . $name . "Model.php";
                if (!file_exists(dirname($path))) {
                    mkdir(dirname($path), 0777, true);
                }

                file_put_contents($path, $template);
                echo "Modelo creado en {$path}\n";
            },
            'handler' => <<<EOT
            <?php

            namespace App\Http\Handlers;
        
            class {$name}Handler {
        
                // Lógica del handler aquí
        
            }
            ?>
            EOT,
            'interface' => <<<EOT
            <?php

            namespace App\Http\Interfaces;

            interface {$name}Interface {

                // Métodos de la interfaz aquí

            }
            ?>
            EOT,
            'component' => <<<EOT
            @extends('main')

            @section('content')
                <h1>Component {$name} it's working </h1>
            @endsection
            EOT,
            'view' => <<<EOT
                <h1>View {$name} it's working </h1>
            EOT,
        ];

        if (!isset($directories[$type])) {
            echo "Tipo de archivo no válido: $type\n";
            exit(1);
        }

        $path = $directories[$type] . $name . ucfirst($type) . ".php";
        
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $templates[$type]);
        echo ucfirst($type) . " creado en {$path}\n";
    }

    /**
     * Intenta abrir una URL en el navegador por defecto del sistema.
     * @param string $url La URL a abrir.
     */
    private function openBrowser(string $url): void
    {
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
