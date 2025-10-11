<?php

namespace App\Core\Cli;

class MakeCommand{

    /**
     * Constructor de la clase MakeCommand.
     * @param string $type El tipo de archivo.
     * @param string $name El nombre del archivo.
     * @param array|null $args Los argumentos de línea de comandos.
     */
    public function __construct(string $type, string $name, ?array $args){
        if(method_exists($this, $type)) $this->{$type}($name, $args);
        else {
            echo "Tipo de archivo no válido: $type\n";
            exit(1);
        }
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
     * Crea un nuevo archivo basado en el tipo y nombre.
     * @param string $type El tipo de archivo.
     * @param string $name El nombre del archivo.
     */
    private function makeFileFromStub(string $type, string $name, string $fileName = "default"){

        $directories = [
            'controller' => dirname(__DIR__, 2) . "/Http/Controllers/",
            'handler'    => dirname(__DIR__, 2) . "/Http/Handlers/",
            'interface'  => dirname(__DIR__, 2) . "/Http/Interfaces/",
            'component'  => dirname(__DIR__, 3) . "/web/componentes/",
            'view'       => dirname(__DIR__, 3) . "/web/views/",
            'factory'    => dirname(__DIR__, 3) . "/Database/Factories/",
            'seeder'     => dirname(__DIR__, 3) . "/Database/Seeders/",
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
        
        $path = dirname(__DIR__, 3) . "/Database/Migrations/" . $fileName;

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
        
        $path = dirname(__DIR__, 2) . "/models/" . $name . "Model.php";

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        file_put_contents($path, $content);
        echo "Modelo creado en {$path}\n";

    }
}