<?php

namespace App\Http\Handlers;

use App\Models\EstadosProducto;
use App\Core\Database;
use DateTime;
use PDO;

class EstadosProductoHandler {

    /**
     * @var Database
     * @access private
     * 
     */
    private Database $db;

    /**
     * @return void
     * @access public
     * 
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(EstadosProducto $estadosProducto): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `estados_producto` 
            (`nombre`) 
            VALUES (?)'
        );

        $stmt->execute([
            $estadosProducto->getNombre()
        ]);

        return $db->lastInsertId();
    }

    public function update(EstadosProducto $estadosProducto): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `estados_producto` SET 
            `nombre` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $estadosProducto->getNombre(),
            $estadosProducto->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `estados_producto` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?EstadosProducto {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `estados_producto` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        $stmt->closeCursor();

        return $datos ? new EstadosProducto(
            $datos['id'],
            $datos['nombre']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `estados_producto`')
            ->fetchAll(PDO::FETCH_ASSOC);
            

        return array_map(
            fn($row) => new EstadosProducto(
                $row['id'],
                $row['nombre']
            ),
            $result
        ) ?? [];
    }
}