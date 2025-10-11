<?php

namespace App\Database;

class Blueprint
{
    private string $tableName;
    private array $columns = [];
    private array $primaryKey = [];
    private array $indexes = [];
    private array $foreignKeys = [];

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    // --- Métodos para definir tipos de columnas ---

    public function id(string $columnName = 'id'): void
    {
        $this->columns[] = "`{$columnName}` INT AUTO_INCREMENT";
        $this->primaryKey[] = $columnName;
    }

    public function string(string $name, int $length = 255): self
    {
        $this->columns[] = "`{$name}` VARCHAR({$length}) NOT NULL";
        return $this;
    }

    public function text(string $name): self
    {
        $this->columns[] = "`{$name}` TEXT NOT NULL";
        return $this;
    }

    public function integer(string $name): self
    {
        $this->columns[] = "`{$name}` INT NOT NULL";
        return $this;
    }

    public function unsignedInteger(string $name): self
    {
        $this->columns[] = "`{$name}` INT UNSIGNED NOT NULL";
        return $this;
    }

    public function timestamps(): void
    {
        $this->columns[] = '`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP';
        $this->columns[] = '`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
    }

    public function foreignId(string $columnName): self
    {
        $this->unsignedInteger($columnName);
        $relatedTable = str_replace('_id', '', $columnName) . 's';
        $this->foreignKeys[] = "CONSTRAINT `{$this->tableName}_{$columnName}_foreign` " .
                                "FOREIGN KEY (`{$columnName}`) REFERENCES `{$relatedTable}`(`id`) " .
                                "ON DELETE CASCADE";
        return $this;
    }

    // --- Métodos para modificar la última columna añadida ---

    public function nullable(): self
    {
        $lastColumnIndex = count($this->columns) - 1;
        if (isset($this->columns[$lastColumnIndex])) {
            $this->columns[$lastColumnIndex] = str_replace(' NOT NULL', ' NULL', $this->columns[$lastColumnIndex]);
        }
        return $this;
    }

    public function unique(): self
    {
        $lastColumn = end($this->columns);
        $columnName = explode(' ', $lastColumn)[0];
        $this->indexes[] = "UNIQUE KEY `{$this->tableName}_{$columnName}_unique` ({$columnName})";
        return $this;
    }

    /**
     * Construye la sentencia SQL final para crear la tabla.
     */
    public function toSql(): string
    {
        $parts = $this->columns;

        if (!empty($this->primaryKey)) {
            $parts[] = "PRIMARY KEY (`" . implode('`, `', $this->primaryKey) . "`)";
        }
        
        $parts = array_merge($parts, $this->indexes, $this->foreignKeys);

        $sql = "CREATE TABLE `{$this->tableName}` (\n  ";
        $sql .= implode(",\n  ", $parts);
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        
        return $sql;
    }
}