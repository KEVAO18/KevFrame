<?php

namespace App\Models;

use App\Http\Interfaces\UsuariosInterface;

class Usuario implements UsuariosInterface
{
    private int $dni;
    private string $fullname;
    private string $userName;
    private string $email;
    private string $pass;
    private string $salt;
    private bool $usuario_activo;

    public function __construct() {}

    public function getDni(): int {
        return $this->dni;
    }

    public function getFullname(): string {
        return $this->fullname;
    }

    public function getUserName(): string {
        return $this->userName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPass(): string {
        return $this->pass;
    }

    public function getSalt(): string {
        return $this->salt;
    }

    public function getIsUsuarioActivo(): bool {
        return $this->usuario_activo;
    }

}