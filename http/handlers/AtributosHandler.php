<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Atributo;
use PDO;

class AtributosHandler {
    
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

    /**
     * @param Atributo $atributos
     * @return void
     * @access public
     * 
     */
    public function create(Atributo $atributos): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `atributos` 
            (`nombre`) 
            VALUES (?)'
        );

        $stmt->execute([
            $atributos->getNombre()
        ]);

        return $db->lastInsertId();
    }

    /**
     * 
     * @param Atributo $atributos
     * @return bool
     * @access public
     * 
     */
    public function update(Atributo $atributos): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `atributos` SET
            `nombre` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $atributos->getNombre(),
            $atributos->getId()
        ]);
    }

    /**
     *
     * @param int $id
     * @return bool
     * @access public
     * 
     */
    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `atributos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    /**
     * 
     * @param int $id
     * @return array|null
     * @access public
     *
     */
    public function getById(int $id): ?Atributo {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `atributos` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        return $datos ? new Atributo(
            $datos['id'], 
            $datos['nombre']
        ): null;
    }

    /**
     *
     * @return array
     * @access public
     * 
     */
    public function getAll(): array {
        $db = $this->db->getConnection();
        $restult = $db->query('SELECT * FROM `atributos`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Atributo(
                $row['id'], 
                $row['nombre']
            ),
            $restult
        ) ?? [];
    }
}
