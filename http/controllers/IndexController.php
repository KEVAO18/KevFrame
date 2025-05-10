<?php

namespace App\Http\Controllers;

class IndexController
{
    public function index()
    {
        // ob_start();
        // require __DIR__ . '/public/index.php';
        // $salida = ob_get_clean();
        // echo $salida;

        echo __DIR__;
    }
}