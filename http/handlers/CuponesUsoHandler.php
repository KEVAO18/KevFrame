<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CuponesUsoInterface;
use App\Core\Database;
use App\Models\CuponesUso;
use DateTime;
use PDO;

class CuponesUsoHandler {

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
        $this->db = new Database();
    }

    public function create(CuponesUsoInterface $cuponesUso): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `cupones_uso` 
            (`cupon`, `usuario`, `fecha_uso`) 
            VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $cuponesUso->getCupon(),
            $cuponesUso->getUsuario(),
            $cuponesUso->getFechaUso()
        ]);

        return $db->lastInsertId();
    }

    public function update(CuponesUsoInterface $cuponesUso): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `cupones` SET
            `cupon` = ?, `usuario` = ?, `fecha_uso` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $cuponesUso->getCupon(),
            $cuponesUso->getUsuario(),
            $cuponesUso->getFechaUso(),
            $cuponesUso->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `cupones` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?CuponesUso {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `cupones` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        return $datos? new CuponesUso(
            $datos['id'],
            $datos['cupon'],
            $datos['usuario'],
            new DateTime($datos['fecha_uso'] )
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `cupones`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new CuponesUso(
                $row['id'],
                $row['cupon'],
                $row['usuario'],
                new DateTime($row['fecha_uso'])
            ), 
            $result
        );
    }
}