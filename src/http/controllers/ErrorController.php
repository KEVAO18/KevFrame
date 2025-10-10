<?php

namespace App\Http\Controllers;

use App\Core\View;

class ErrorController
{

    public function __construct(int $code = 404) {
        $error = [
            '400' => 'badRequest',
            '401' => 'unauthorized',
            '402' => 'paymentRequired',
            '403' => 'forbidden',
            '404' => 'notFound',
            '405' => 'notAllowed',
            '406' => 'notAcceptable',
            '407' => 'proxyAuthenticationRequired',
            '408' => 'requestTimeout',
            '409' => 'conflict',
            '410' => 'gone',
            '500' => 'internalError',
            '501' => 'notImplemented',
            '502' => 'badGateway',
            '503' => 'serviceUnavailable',
            '504' => 'gatewayTimeout',
            '505' => 'httpVersionNotSupported'
        ];

        echo (isset($error[$code]))? $this->{$error[$code]}() : $this->notFound();
    }

    /**
     * Muestra la página de error 400 Solicitud incorrecta
     * 
     * @return void
     */
    public function badRequest(){
        http_response_code(400);
        echo 'Solicitud incorrecta';
    }

    /**
     * Muestra la página de error 401 No autorizado
     *
     * @return void
     */
    public function unauthorized(){
        http_response_code(401);
        echo 'No autorizado';
    }

    public function paymentRequired(){
        http_response_code(402);
        echo 'Pago requerido';
    }

    /**
     * Muestra la página de error 403 Acceso denegado
     *
     * @return void
     */
    public function forbidden(){
        http_response_code(403);
        echo 'Acceso denegado';
    }

    /**
     * Muestra la página de error 404 Página no encontrada
     *
     * @return void
     *
     */
    public function notFound(){
        http_response_code(404);

        $error = [
            'ErrorCode' => '404',
            'msg' => 'Página no encontrada'
        ];
        View::render('errors/404', $error);
    }
    
    /**
     * Muestra la página de error 405 Método no permitido
     *
     * @return void
     */
    public function notAllowed(){
        http_response_code(405);
        echo 'Método no permitido';
    }

    /**
     * Muestra la página de error 406 No aceptable
     *
     * @return void
     */
    public function notAcceptable(){
        http_response_code(406);
        echo 'No aceptable';
    }

    /**
     * Muestra la página de error 407 Autenticación de proxy requerida
     *
     * @return void
     */
    public function proxyAuthenticationRequired(){
        http_response_code(407);
        echo 'Autenticación de proxy requerida';
    }

    /**
     * Muestra la página de error 408 Tiempo de espera agotado
     *
     * @return void
     * 
     */ 
    public function requestTimeout(){
        http_response_code(408);
        echo 'Tiempo de espera agotado';
    }

    /**
     * Muestra la página de error 409 Conflicto
     *
     * @return void
     */
    public function conflict(){
        http_response_code(409);
        echo 'Conflicto';
    }

    /**
     * Muestra la página de error 410 El recurso solicitado ya no está disponible
     *
     * @return void
     */
    public function gone(){
        http_response_code(410);
        echo 'El recurso solicitado ya no está disponible';
    }

    /**
     * Muestra la página de error 411 Longitud requerida
     *
     * @return void
     */
    public function internalError(){
        http_response_code(500);
        echo 'Error interno del servidor';
    }

    public function notImplemented(){
        http_response_code(501);
        echo 'No implementado';
    }

    public function badGateway(){
        http_response_code(502);
        echo 'Puerta de enlace incorrecta';
    }

    public function serviceUnavailable(){
        http_response_code(503);
        echo 'Servicio no disponible';
    }

    public function gatewayTimeout(){
        http_response_code(504);
        echo 'Tiempo de espera de puerta de enlace agotado';
    }

    public function httpVersionNotSupported(){
        http_response_code(505);
        echo 'Versión HTTP no soportada';
    }

}