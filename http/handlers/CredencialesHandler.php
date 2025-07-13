<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Credenciales;
use PDO;

class CredencialesHandler {

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

    public function create(Credenciales $credenciales): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `credenciales` 
            (`usuario`, `tipo`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $credenciales->getUsuario()->getDni(),
            $credenciales->getTipo()->getId()
        ]);

        return $db->lastInsertId();
    }

    public function update(Credenciales $credenciales): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `credenciales` SET
            `usuario` = ?, `tipo` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $credenciales->getUsuario()->getDni(),
            $credenciales->getTipo()->getId(),
            $credenciales->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `credenciales` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?Credenciales {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `credenciales` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Credenciales(
            $datos['id'],
            (new UsuariosHandler)->getById($datos['usuario']),
            (new TipoCredencialHandler)->getById($datos['tipo'])
        ) : null;
    }

    public function getByUser(int $id): ?Credenciales {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `credenciales` WHERE `usuario` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Credenciales(
            $datos['id'],
            (new UsuariosHandler)->getById($datos['usuario']),
            (new TipoCredencialHandler)->getById($datos['tipo'])
        ) : null;
    }

    public function getByType(int $id): ?Credenciales {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `credenciales` WHERE `tipo` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Credenciales(
            $datos['id'],
            (new UsuariosHandler)->getById($datos['usuario']),
            (new TipoCredencialHandler)->getById($datos['tipo'])
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `credenciales`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new Credenciales(
                $datos['id'],
                (new UsuariosHandler)->getById($datos['usuario']),
                (new TipoCredencialHandler)->getById($datos['tipo'])
            ),
            $result
        );
    }
}
