<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\UsuariosInterface;
use App\Core\Database;
use PDO;

class UsuariosHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function create(UsuariosInterface $usuario): int
    {
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

    public function update(UsuariosInterface $usuario): bool
    {
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

    public function delete(int $dni): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `usuarios` WHERE `dni` = ?');
        return $stmt->execute([$dni]);
    }

    public function getById(int $dni): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT `dni`, `fullname`, `userName`, `email`, `pass`, `salt`, `usuario_activo` FROM `usuarios` WHERE `dni` = ?');
        $stmt->execute([$dni]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT `dni`, `fullname`, `userName`, `email`, `pass`, `salt`, `usuario_activo` FROM `usuarios`')
        ->fetchAll(PDO::FETCH_ASSOC);
    }
}