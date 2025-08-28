<?php

namespace App\Core;

final class SessionManager{
    private bool $sessionStarted = false;
    private const SESSION_INITIATED_KEY = '__session_initiated'; // Clave para detectar si la sesión fue iniciada correctamente

    private function __construct(){
        
    }

    /**
     * Inicia la sesión y aplica las configuraciones de seguridad recomendadas.
     * Debe llamarse al principio de cada script que necesite la sesión.
     */
    public function start(): void{
        if ($this->sessionStarted) {
            return; // La sesión ya está iniciada
        }

        // 1. Configurar los parámetros de la cookie de sesión
        // ¡IMPORTANTE! Asegúrate de que 'domain' sea tu dominio real.
        $cookieDomain = $_SERVER['HTTP_HOST'];
        if (strpos($cookieDomain, ':') !== false) {
            // Si el host incluye un puerto, lo removemos para el dominio
            $cookieDomain = parse_url('http://' . $cookieDomain.'/aromas/prueba/', PHP_URL_HOST);
        }
        $cookieDomain = ($cookieDomain === 'localhost/aromas/prueba/' || filter_var($cookieDomain, FILTER_VALIDATE_IP)) ? '' : '.' . $cookieDomain;


        session_set_cookie_params([
            'lifetime' => 0,          // La cookie expira cuando se cierra el navegador
            'path' => '/',            // La cookie estará disponible en todo el dominio
            'domain' => $cookieDomain, // Dominio para el que es válida la cookie
            'secure' => $this->isHttps(), // Solo se envía sobre HTTPS
            'httponly' => true,       // No accesible por JavaScript
            'samesite' => 'Lax'       // Protección CSRF
        ]);

        // 2. Iniciar la sesión
        session_start();
        $this->sessionStarted = true;

        // 3. Prevenir ataques de fijación de sesión: regenerar ID si es un nuevo inicio de sesión
        // o si los privilegios del usuario cambian (ej. después de un login exitoso)
        if (!isset($_SESSION[self::SESSION_INITIATED_KEY])) {
            session_regenerate_id(true); // El 'true' destruye la antigua sesión
            $_SESSION[self::SESSION_INITIATED_KEY] = true;
        }

        // 4. Actualizar el timestamp de última actividad
        $_SESSION['last_activity'] = time();
    }

    /**
     * Establece un valor en la sesión.
     * @param string $key La clave del dato.
     * @param mixed $value El valor a almacenar.
     */
    public function set(string $key, $value): void{
        $_SESSION[$key] = $value;
    }

    /**
     * Obtiene un valor de la sesión.
     * @param string $key La clave del dato.
     * @param mixed $default El valor por defecto si la clave no existe.
     * @return mixed
     */
    public function get(string $key, $default = null){
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verifica si una clave existe en la sesión.
     * @param string $key La clave a verificar.
     * @return bool
     */
    public function has(string $key): bool{
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina una clave de la sesión.
     * @param string $key La clave a eliminar.
     */
    public function remove(string $key): void{
        unset($_SESSION[$key]);
    }

    /**
     * Cierra la sesión actual (destruye los datos y el ID de sesión).
     */
    public function destroy(): void{
        if ($this->sessionStarted) {
            session_unset();     // Vacía todas las variables de sesión
            session_destroy();   // Destruye la sesión en el servidor
            // Eliminar la cookie de sesión del navegador
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
            $this->sessionStarted = false;
        }
    }

    /**
     * Regenera el ID de sesión, útil después de un inicio de sesión exitoso o cambio de privilegios.
     */
    public function regenerateId(): void{
        if ($this->sessionStarted) {
            session_regenerate_id(true); // 'true' elimina el archivo de sesión antiguo en el servidor
            $_SESSION[self::SESSION_INITIATED_KEY] = true; // Restablecer la marca de inicio
        }
    }

    /**
     * Comprueba si la conexión actual es HTTPS.
     */
    private function isHttps(): bool{
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ||
            isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443;
    }

    // Métodos para manejar tiempos de inactividad o sesiones caducadas
    public function isExpired(int $timeoutMinutes = 30): bool{
        $lastActivity = $this->get('last_activity', 0);
        return (time() - $lastActivity) > ($timeoutMinutes * 60);
    }

}
