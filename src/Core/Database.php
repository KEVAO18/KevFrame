<?php

namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Exception;

final class Database {
    
    /**
     * Devuelve la única instancia de la clase Database (patrón Singleton).
     *
     * @return self
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
     *
     * @return void
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Establece la conexión con la base de datos.
     *
     * @return void
     */
    private function connect() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET'];

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Proporciona una conexión PDO "cruda" solo al servidor MySQL, sin seleccionar una base de datos.
     * Esencial para tareas administrativas como 'db:create'.
     *
     * @return PDO
     */
    public static function getRawConnection(): PDO {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET'];
        
        // El DSN no incluye 'dbname', que es la clave para que esto funcione.
        $dsn = "mysql:host={$host};charset={$charset}";

        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            throw new \Exception("No se pudo conectar al servidor de base de datos en '{$host}': " . $e->getMessage());
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
            if ($_ENV['APP_ENV'] === 'production') {
                $errorMessage = "[" . date("Y-m-d H:i:s") . "] " . $e->getMessage() . "\n";
                error_log($errorMessage, 3, __DIR__ . '/../../logs/db_errors.log');

                http_response_code(500);
                \App\Core\View::render('errors/GeneralError', [
                    'ErrorCode' => '500',
                    'msg' => 'Error Interno del Servidor'
                ]);
                exit();
            } else {
                throw $e;
            }
        }
    }

    /**
     * Devuelve la única instancia de la clase Database (patrón Singleton).
     *
     * @return self
     */
    public static function getInstance(): self {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
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
