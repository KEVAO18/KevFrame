<?php

namespace App\Http\Controllers;

class ErrorController
{
    public function notFound()
    {
        http_response_code(404);
        echo 'Página no encontrada';
    }
}