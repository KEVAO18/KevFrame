<?php

namespace App\Core;

use App\Core\Cli\DbCommand;
use App\Core\Cli\MakeCommand;

/**
 * El motor de la interfaz de línea de comandos (CLI) de KevFrame.
 * Orquesta todos los comandos de desarrollo, desde la creación de archivos
 * hasta la gestión completa del ciclo de vida de la base de datos.
 */
class Cli {

    // ---------------------------------------------------------------
    //                         No commands
    // ---------------------------------------------------------------
    private const VERSION = "0.8.0";

    public function getVersion(){
        return self::VERSION;
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
     * El método principal para despachar los comandos.
     * @param array $arguments Los argumentos pasados desde la línea de comandos.
     */
    public function run(array $arguments): void{
        
        // Se obtiene el comando y se separa de los argumentos
        $command = array_shift($arguments);

        // Si no se pasa ningún comando, muestra la ayuda
        if ($command === null) {
            $this->help();
            return;
        }
        
        // se separa el comando en dos partes por ejemplo 
        // make:controller -> [make, controller] o db:migrate -> [db, migrate]
        $methodName = explode(":", $command);

        $commandTypeList = [
            "serve",
            "help",
            "version",
            "make",
            "db",
        ];

        // Verifica si el método existe y si el comando es válido
        if (method_exists($this, $methodName[0]) && in_array($methodName[0], $commandTypeList)) {

            try {

                if(count($methodName) > 1){
                    
                    // Si el comando tiene argumentos, los pasa al método
                    $this->{$methodName[0]}($methodName[1], array_shift($arguments), $arguments);
                    exit(0);

                }

                // Si el comando no tiene argumentos, llama al método sin pasar argumentos
                $this->{$methodName[0]}($arguments);
                exit(0);

            } catch (\Throwable $th) {

                // Si hay un error, muestra un mensaje y la ayuda
                echo "Comando no reconocido: $command\n";
                $this->help();
                exit(1);
            }

        }

        // Si el comando no es válido, muestra un mensaje y la ayuda
        echo "Comando no reconocido: $command\n";
        $this->help();
        exit(1);

    }


    // ---------------------------------------------------------------
    //                         Main commands
    // ---------------------------------------------------------------


    /**
     * Muestra la versión y la ayuda de los comandos.
     */
    public function version(): void{
        echo "╔═════════════════╗\n";
        echo "║     █     █     ║\n";
        echo "║     █     █     ║\n";
        echo "║     █    █      ║\n";
        echo "║     █▄▄▀▀       ║\n";
        echo "║     █  ▀▄       ║\n";
        echo "║     █    ▀▄     ║\n";
        echo "║     █     █     ║\n";
        echo "║     █     █     ║\n";
        echo "╚═════════════════╝\n";
        echo "Version " . self::VERSION . "\n";
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
        echo "  db:migrate            Runs the database migrations.\n";
        echo "  make:migration        Creates a new migration file.\n";
        echo "  make:factory          Creates a new factory file.\n";
        echo "  make:seeder           Creates a new seeder file.\n";
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
        
        (new MakeCommand($type, $name, $args));

    }

    /**
     * Ejecuta comandos relacionados con la base de datos.
     * @param string $command El comando a ejecutar.
     */
    private function db(string $command) {
        if (!$command) {
            echo "Debes indicar un comando para la base de datos (e.g., db:migrate).\n";
            exit(1);
        }

        (new DbCommand($command));

    }

}
