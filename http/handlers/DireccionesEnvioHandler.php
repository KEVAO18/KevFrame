<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\DireccionesEnvioInterface;
use App\Models\DireccionesEnvio;
use App\Core\Database;
use PDO;

class DireccionesEnvioHandler {
    
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

    public function create(DireccionesEnvioInterface $direccionesEnvio): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `direcciones_envio` 
            (`usuario`, `direccion`, `ciudad`, `departamento`, `pais`, `principal`) 
            VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $direccionesEnvio->getUsuario(),
            $direccionesEnvio->getDireccion(),
            $direccionesEnvio->getCiudad(),
            $direccionesEnvio->getDepartamento(),
            $direccionesEnvio->getPais(),
            $direccionesEnvio->getPrincipal()
        ]);

        return $db->lastInsertId();
    }

    public function update(DireccionesEnvioInterface $direccionesEnvio): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `direcciones_envio` SET 
            `usuario` = ?, `direccion` = ?, `ciudad` = ?, `departamento` = ?, `pais` = ?, `principal` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $direccionesEnvio->getUsuario(),
            $direccionesEnvio->getDireccion(),
            $direccionesEnvio->getCiudad(),
            $direccionesEnvio->getDepartamento(),
            $direccionesEnvio->getPais(),
            $direccionesEnvio->getPrincipal(),
            $direccionesEnvio->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `direcciones_envio` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?DireccionesEnvio {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `direcciones_envio` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos? new DireccionesEnvio(
            $datos['id'],
            $datos['usuario'],
            $datos['direccion'],
            $datos['ciudad'],
            $datos['departamento'],
            $datos['pais'],
            $datos['principal'] 
        ) : null;
    }

    public function getByUser(int $user): ?DireccionesEnvio {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `direcciones_envio` WHERE `usuario` = ?');
        $stmt->execute([$user]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos? new DireccionesEnvio(
            $datos['id'],
            $datos['usuario'],
            $datos['direccion'],
            $datos['ciudad'],
            $datos['departamento'],
            $datos['pais'],
            $datos['principal'] 
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `direcciones_envio`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn ($datos) => new DireccionesEnvio(
                $datos['id'],
                $datos['usuario'],
                $datos['direccion'],
                $datos['ciudad'],
                $datos['departamento'],
                $datos['pais'],
                $datos['principal']
            ),
            $result
        ) ?? [];
    }
}