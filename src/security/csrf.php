<?php

namespace App\Security;

use App\Core\SessionManager;

class CSRF
{
    /**
     * Genera y devuelve un token CSRF único.
     */
    public static function generateToken(): string
    {
        $sm = SessionManager::getInstance();
        $sm->start();

        if (!$sm->has('_token')) {
            $token = bin2hex(random_bytes(32));
            $sm->set('_token', $token);
        }

        return $sm->get('_token');
    }

    /**
     * Verifica el token CSRF para peticiones que modifican datos.
     */
    public static function verifyToken(): void
    {
        $sm = SessionManager::getInstance();
        $sm->start();

        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
            $submittedToken = $_POST['_token'] ?? '';
            $sessionToken = $sm->get('_token');

            if (!$sessionToken || !hash_equals($sessionToken, $submittedToken)) {
                http_response_code(403);
                die('Error de validación CSRF.');
            }
        }
    }
}