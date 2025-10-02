<?php

namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;
use Exception;

final class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET']; // Cambia el conjunto de caracteres según tu necesida

        try {
            $this->connection = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $user, $pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * Ejecuta una consulta SQL de forma segura utilizando sentencias preparadas.
     *
     * @param string $sql La consulta SQL con placeholders (ej. ?, ?, :name).
     * @param array $params Un array de parámetros para vincular a la consulta.
     * @return \PDOStatement|false El objeto PDOStatement o false si hay un error.
     */
    public function query(string $sql, array $params = [])
    {
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

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }
}
