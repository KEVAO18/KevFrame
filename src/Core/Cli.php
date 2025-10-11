<?php

namespace App\Core;

use App\Core\Cli\Generator;

class Cli
{
    private const VERSION = "0.8.0";

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
        echo "Developed by KevaoDev\n";
        echo "my portfolio https://www.kevao.tech/\n";
        echo "\nAvailable commands:\n";
        echo "  serve                 Start development server\n";
        echo "  db:create             Creates the database defined in the .env if it does not exist.\n";
        echo "  db:seed               Seeds the database using the seeders.\n";
        echo "  make:migration        Creates a new migration file.\n";
        echo "  make:factory          Creates a new factory file.\n";
        echo "  make:controller       Create a new controller\n";
        echo "  make:model            Create a new model\n";
        echo "  make:handler          Create a new handler\n";
        echo "  make:interface        Create a new interface\n";
        echo "  make:component        Create a new component\n";
        echo "  make:view             Create a new view\n";
        echo "  version               Show the version of the application\n";
        echo "  help                  Show this help message\n";
        echo "\nUsage: php kev [command]:[type] [argument or name] [--option=value] \n";
        echo "  command: The command to execute (e.g., make, db).\n";
        echo "  type: The type of resource to create (e.g., controller, model).\n";
        echo "  argument or name: The name of the resource to create.\n";
        echo "  --option=value: Optional arguments for the command.\n";

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

        if(method_exists($this, $type)) $this->{$type}($name, $args);
        else {
            echo "Tipo de archivo no válido: $type\n";
            exit(1);
        }

    }

    /**
     * Crea un nuevo archivo basado en el tipo y nombre.
     * @param string $type El tipo de archivo.
     * @param string $name El nombre del archivo.
     */
    private function makeFileFromStub(string $type, string $name, string $fileName = "default"){

        $directories = [
            'controller' => dirname(__DIR__) . "/Http/Controllers/",
            'handler'    => dirname(__DIR__) . "/Http/Handlers/",
            'interface'  => dirname(__DIR__) . "/Http/Interfaces/",
            'component'  => dirname(__DIR__, 2) . "/web/componentes/",
            'view'       => dirname(__DIR__, 2) . "/web/views/",
            'factory'    => dirname(__DIR__, 2) . "/Database/Factories/",
            'seeder'     => dirname(__DIR__, 2) . "/Database/Seeders/",
        ];

        if (!isset($directories[$type])) {
            exit(1);
        }

        // 2. Usamos el Generator para obtener el contenido
        $content = Generator::get($type, ['name' => $name]);

        if($fileName == "default"){
            $fileName = $name . ucfirst($type) . ".php";
        }
        
        $path = $directories[$type] . $fileName;

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $content);
        echo ucfirst($type) . " creado en {$path}\n";

    }

    /**
     * Genera un nuevo archivo de vista.
     */
    private function View(string $name, ?array $args): void{
        $fileName = $name . ".php";
        $this->makeFileFromStub('view', $name, $fileName);
    }

    /**
     * Genera un nuevo archivo de semillero.
     */
    private function Seeder(string $name, ?array $args): void{
        $this->makeFileFromStub('seeder', $name);
    }

    /**
     * Genera un nuevo archivo de fábrica.
     */
    private function Factory(string $name, ?array $args): void{
        $this->makeFileFromStub('factory', $name);
    }

    /**
     * Genera un nuevo archivo de migración.
     */
    private function Migration(string $name, ?array $args): void{

        $argumentos = $this->getArgs($args);

        $timestamp = date('Y_m_d_His');

        $fileName = $timestamp . "_" . $name . ".php";

        $content = Generator::get('migration', [
            'table' => $argumentos['table'], 
            'timestamp' => $timestamp
        ]);
        
        $path = dirname(__DIR__, 2) . "/Database/Migrations/" . $fileName;

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $content);
        echo "Migration creado en {$path}\n";
    }

    /**
     * Genera un nuevo archivo de componente.
     */
    private function Component(string $name, ?array $args): void{
        $this->makeFileFromStub('component', $name);
    }

    /**
     * Genera un nuevo archivo de interfaz.
     */
    private function Interface(string $name, ?array $args): void{
        $this->makeFileFromStub('interface', $name);
    }

    /**
     * Genera un nuevo archivo de manejador.
     */
    private function Handler(string $name, ?array $args): void{
        $this->makeFileFromStub('handler', $name);
    }

    /**
     * Genera un nuevo archivo de controlador.
     */
    private function Controller(string $name, ?array $args): void{
        $this->makeFileFromStub('controller', $name);
    }

    /**
     * Genera un nuevo archivo de modelo.
     */
    private function Model(string $name, ?array $args): void{

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
     * Crea la tabla 'migrations' si no existe.
     */
    private function ensureMigrationsTableExists(): void {
        Database::getInstance()->query(
            "CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB"
        );
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
