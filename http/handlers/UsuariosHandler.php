<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\UsuariosInterface;
use App\Core\Database;
use PDO;
use App\Models\Usuario;

class UsuariosHandler{

    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }
    
    public function create(UsuariosInterface $usuario): int{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `usuarios` 
            (`dni`, `fullname`, `userName`, `email`, `pass`, `salt`, `usuario_activo`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $usuario->getDni(),
            $usuario->getFullname(),
            $usuario->getUserName(),
            $usuario->getEmail(),
            $usuario->getPass(),
            $usuario->getSalt(),
            $usuario->getIsUsuarioActivo()
        ]);

        return $db->lastInsertId();
    }

    public function update(UsuariosInterface $usuario): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `usuarios` SET
            `fullname` = ?, `userName` = ?, `email` = ?, `pass` = ?, `salt` = ?, `usuario_activo` = ? WHERE `dni` = ?'
        );

        return $stmt->execute([
            $usuario->getFullname(),
            $usuario->getUserName(),
            $usuario->getEmail(),
            $usuario->getPass(),
            $usuario->getSalt(),
            $usuario->getIsUsuarioActivo(),
            $usuario->getDni()
        ]);
    }

    public function delete(int $dni): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `usuarios` WHERE `dni` = ?');
        return $stmt->execute([$dni]);
    }

    public function getById(int $dni): ?Usuario{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT `dni`, `fullname`, `userName`, `email`, `usuario_activo` FROM `usuarios` WHERE `dni` = ?');
        $stmt->execute([$dni]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Usuario(
            $datos['dni'],
            $datos['fullname'],
            $datos['userName'],
            $datos['email'],
            "",
            "",
            $datos['usuario_activo']
        ) : null;
    }

    public function getAll(): array{
        $db = $this->db->getConnection();
        $result = $db->query('SELECT `dni`, `fullname`, `userName`, `email`, `usuario_activo` FROM `usuarios`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Usuario(
                $row['dni'],
                $row['fullname'],
                $row['userName'],
                $row['email'],
                "",
                "",
                $row['usuario_activo']
            ),
            $result
        ) ?? [];
    }
}