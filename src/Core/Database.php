<?php

namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Exception;

/**
 * Clase Database que implementa el patrón Singleton para gestionar la conexión PDO.
 * Soporta MySQL, SQL Server (sqlsrv) y SQLite de forma condicional.
 */
final class Database {
    
    /**
     * Almacena la única instancia de la clase Database.
     *
     * @var self|null
     */
    private static $instance = null;
    
    /**
     * La conexión PDO a la base de datos.
     *
     * @var PDO
     */
    private $connection;

    /**
     * Constructor privado para implementar el patrón Singleton.
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Establece la conexión con la base de datos (seleccionando una base de datos específica).
     *
     * @return void
     * @throws Exception Si el driver es inválido o faltan variables de entorno.
     */
    private function connect() {
        // Cargar las variables de entorno
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $driver = strtolower($_ENV['APP_DB_DRIVER'] ?? 'mysql'); 
        $dsn = '';
        $user = null;
        $pass = null;

        try {
            switch ($driver) {
                case 'mysql':
                    $host = $_ENV['DB_HOST'] ?? throw new Exception("DB_HOST no definido para MySQL.");
                    $dbname = $_ENV['DB_NAME'] ?? throw new Exception("DB_NAME no definido para MySQL.");
                    $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
                    $user = $_ENV['DB_USER'] ?? '';
                    $pass = $_ENV['DB_PASS'] ?? '';
                    
                    $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
                    break;
                
                case 'sqlsrv':
                    $host = $_ENV['DB_SQLSERVER_HOST'] ?? throw new Exception("DB_SQLSERVER_HOST no definido.");
                    $dbname = $_ENV['DB_SQLSERVER_NAME'] ?? throw new Exception("DB_SQLSERVER_NAME no definido para SQL Server.");
                    $user = $_ENV['DB_SQLSERVER_USER'] ?? '';
                    $pass = $_ENV['DB_SQLSERVER_PASS'] ?? '';
                    
                    // DSN para SQL Server (incluye Database, necesario para conexión exitosa)
                    $dsn = "sqlsrv:Server={$host};Database={$dbname}";
                    break;
                
                case 'sqlite':
                    // Requiere la variable DB_SQLITE_PATH en tu .env
                    $path = $_ENV['DB_SQLITE_PATH'] ?? throw new Exception("DB_SQLITE_PATH no definido.");
                    $dsn = "sqlite:{$path}";
                    break;

                default:
                    throw new Exception("Driver de base de datos no soportado: '{$driver}'.");
            }

            // Establecer la conexión PDO
            $this->connection = new PDO($dsn, $user, $pass);
            
            // Atributos de conexión comunes y seguros
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            // Error de conexión a la BD
            throw new Exception("Error al conectar con el driver '{$driver}': " . $e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * Proporciona una conexión PDO de bajo nivel para tareas administrativas.
     * * - MySQL: Conecta al servidor sin especificar DB (DSN 'crudo').
     * - SQL Server: Conecta a la DB configurada (DSN completo, ya que lo necesita para autenticarse).
     * - SQLite: Conecta al archivo (DSN completo).
     *
     * @return PDO
     * @throws Exception Si el driver es inválido o faltan variables de entorno.
     */
    public static function getRawConnection(): PDO {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $driver = strtolower($_ENV['APP_DB_DRIVER'] ?? 'mysql');
        $dsn = '';
        $user = null;
        $pass = null;

        try {
            switch ($driver) {
                case 'mysql':
                    $host = $_ENV['DB_HOST'] ?? throw new Exception("DB_HOST no definido para MySQL.");
                    $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
                    $user = $_ENV['DB_USER'] ?? '';
                    $pass = $_ENV['DB_PASS'] ?? '';
                    
                    // DSN crudo para MySQL (solo host y charset)
                    $dsn = "mysql:host={$host};charset={$charset}";
                    break;
                
                case 'sqlsrv':
                    $host = $_ENV['DB_SQLSERVER_HOST'] ?? throw new Exception("DB_SQLSERVER_HOST no definido.");
                    // CORRECCIÓN FINAL: Se necesita el nombre de la DB para la autenticación en SQL Server.
                    $dbname = $_ENV['DB_SQLSERVER_NAME'] ?? throw new Exception("DB_SQLSERVER_NAME no definido para SQL Server.");
                    $user = $_ENV['DB_SQLSERVER_USER'] ?? '';
                    $pass = $_ENV['DB_SQLSERVER_PASS'] ?? '';

                    // DSN completo para SQL Server, necesario para la autenticación en el servidor.
                    $dsn = "sqlsrv:Server={$host};Database={$dbname}"; 
                    break;
                
                case 'sqlite':
                    // Para SQLite, la conexión "cruda" es la conexión a la base de datos misma.
                    $path = $_ENV['DB_SQLITE_PATH'] ?? throw new Exception("DB_SQLITE_PATH no definido.");
                    $dsn = "sqlite:{$path}";
                    $user = null;
                    $pass = null;
                    break;
                
                default:
                    throw new Exception("Driver de base de datos no soportado: '{$driver}'");
            }
            
            // Conexión PDO común
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new \Exception("No se pudo conectar al servidor de base de datos ({$driver}): " . $e->getMessage());
        }
    }

    /**
     * Ejecuta una consulta SQL de forma segura utilizando sentencias preparadas.
     *
     * @param string $sql La consulta SQL con placeholders (ej. ?, ?, :name).
     * @param array $params Un array de parámetros para vincular a la consulta.
     * @return \PDOStatement|false El objeto PDOStatement o false si hay un error.
     */
    public function query(string $sql, array $params = []) : \PDOStatement|false {
        try {
            // Prepara la consulta
            $stmt = $this->connection->prepare($sql);

            // Vincula los parámetros
            $stmt->execute($params);

            // Devuelve el statement para que se puedan obtener los resultados
            return $stmt;
        } catch (PDOException $e) {
            // Manejo de errores basado en el entorno (production vs development)
            if (($_ENV['APP_ENV'] ?? 'development') === 'production') {
                $errorMessage = "[" . date("Y-m-d H:i:s") . "] " . $e->getMessage() . "\n";
                error_log($errorMessage, 3, __DIR__ . '/../../logs/db_errors.log');

                http_response_code(500);
                // Aquí se asume que tienes una clase View para renderizar una página de error
                // \App\Core\View::render('errors/GeneralError', ['ErrorCode' => '500', 'msg' => 'Error Interno']);
                exit();
            } else {
                // En desarrollo, lanzamos la excepción para depuración
                throw $e;
            }
        }
    }

    /**
     * Devuelve la única instancia de la clase Database (patrón Singleton).
     *
     * @return self
     */
    public static function getInstance(): static {
        if (!static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Devuelve la conexión PDO actual.
     *
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
}