<?php

namespace App\Models;

use App\Http\Interfaces\UsuariosInterface;

class Usuario implements UsuariosInterface
{
    private int $dni;
    private string $fullname;
    private string $email;
    private string $pass;
    private string $salt;
    private bool $usuario_activo;

    public function __construct(int $dni, string $fullname, string $email, string $pass, string $salt, bool $usuario_activo) {
        $this->dni = $dni;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->pass = $pass;
        $this->salt = $salt;
        $this->usuario_activo = $usuario_activo;
    }

    public function getDni(): int {
        return $this->dni;
    }

    public function getFullname(): string {
        return $this->fullname;
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