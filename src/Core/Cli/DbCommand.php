<?php

namespace App\Core\Cli;

use App\Core\Database;
use App\Database\Seeders\Seeder;
use Dotenv\Dotenv;
use PDO;

class DbCommand {

    /**
     * Ejecuta comandos relacionados con la base de datos.
     * @param string $command El comando a ejecutar.
     */
    public function __construct(string $command) {
        if(method_exists($this, $command)) $this->{$command}();
        else{
            echo "Comando no reconocido: $command\n";
            exit(1);
        }
        
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
     * Crea la base de datos usando la conexión "cruda" de la clase Database.
     */
    private function create(): void {
        try {
            $pdo = Database::getRawConnection(); // Usa el método seguro que no selecciona una BD
            $dotenv = Dotenv::createImmutable(dirname(__DIR__, 3));
            $dotenv->load();
            
            $dbname = $_ENV['DB_NAME'];
            $charset = $_ENV['DB_CHARSET'];

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET {$charset} COLLATE utf8mb4_unicode_ci");
            echo "Base de datos '{$dbname}' creada o ya existente.\n";
        } catch (\Exception $e) {
            die("\033[31mError al crear la base de datos:\033[0m " . $e->getMessage() . "\n");
        }
    }

    /**
     * Ejecuta todas las migraciones de la base de datos que no se han ejecutado previamente.
     */
    private function migrate(): void {
        try {
            $this->ensureMigrationsTableExists();
            $db = Database::getInstance();

            $runMigrations = $db->query("SELECT migration FROM migrations")->fetchAll(PDO::FETCH_COLUMN);
            $migrationFiles = glob(dirname(__DIR__, 3) . '/database/migrations/*.php');
            sort($migrationFiles); // Ordena cronológicamente gracias al timestamp del nombre

            $pendingMigrations = array_filter($migrationFiles, fn($file) => !in_array(basename($file), $runMigrations));

            if (empty($pendingMigrations)) {
                echo "No hay migraciones pendientes por ejecutar.\n";
                return;
            }

            foreach ($pendingMigrations as $file) {
                echo "Migrando: " . basename($file) . "\n";
                $migration = require $file;
                $migration->up();
                $db->query("INSERT INTO migrations (migration) VALUES (?)", [basename($file)]);
            }

            echo "Migraciones completadas exitosamente.\n";
        } catch (\Exception $e) {
            echo "\n\033[31mError durante la migración:\033[0m " . $e->getMessage() . "\n";
            exit(1);
        }
    }

    /**
     * Llenar la base de datos con datos de prueba.
     * 
     * @throws \Exception Si ocurre un error durante la semilla.
     */
    private function seed(): void {
        require_once dirname(__DIR__, 3) . '/database/Seeders/Seeder.php';
        (new Seeder())->run();
    }

}