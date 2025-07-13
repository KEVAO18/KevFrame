<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Carrito;
use DateTime;
use PDO;

class CarritoHandler {
    
    /**
     * @var Database
     * @access private
     *
     */
    private Database $db;

    /**
     * @access public
     * @return void
     * 
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * @return int
     * @access public
     *
     */
    public function create(Carrito $carrito): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `carritos` 
            (`usuario`, `fecha_creacion`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $carrito->getUsuario()->getDni(),
            $carrito->getFechaCreacion()
        ]);

        return $db->lastInsertId();
    }

    /**
     * @param Carrito $carrito
     * @return bool
     * @access public
     *
     */
    public function update(Carrito $carrito): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `carritos` SET
            `usuario` = ?, `fecha_creacion` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $carrito->getUsuario()->getDni(),
            $carrito->getFechaCreacion(),
            $carrito->getId()
        ]);
    }

    /**
     * @param int $id
     * @return bool
     * @access public
     * 
     */
    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `carritos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    /**
     * @param int $id
     * @return Carrito|null
     * @access public
     * 
     */
    public function getById(int $id): ?Carrito {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `carritos` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos? new Carrito(
            $datos['id'],
            (new UsuariosHandler)->getById($datos['usuario']),
            new DateTime($datos['fecha_creacion'])
        ) : null;
    }

    /**
     * @param int $user
     * @return Carrito|null
     * @access public
     * 
     */
    public function getByUserId(int $user): ?Carrito {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `carritos` WHERE `usuario` = ?');
        $stmt->execute([$user]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos? new Carrito(
            $datos['id'],
            (new UsuariosHandler)->getById($datos['usuario']),
            new DateTime($datos['fecha_creacion'])
        ) : null;
    }



    /**
     * @return array
     * @access public
     *
     */
    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `carritos`')
            ->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(
            fn($datos) => new Carrito(
                $datos['id'],
                (new UsuariosHandler)->getById($datos['usuario']),
                new DateTime($datos['fecha_creacion'])
            ),
            $result
        ) ?? [];
    }
}