<?php
namespace App\Core;

use PDO;
use PDOException;
use Dotenv\Dotenv;

final class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $this->connect();
    }

    private function connect() {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
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

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }
}