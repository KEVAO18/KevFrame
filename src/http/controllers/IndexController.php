<?php

namespace App\Http\Controllers;

use App\Core\Cli;
use App\Core\Request;
use App\Core\SessionManager;
use App\Core\View;
use App\Models\UsuarioModel;

class IndexController
{

    public function index()
    {


        $sm = SessionManager::getInstance();
        $sm->start();

        // Elimina los datos de ejemplo y pasa la versión del framework
        $data = [
            'version' => (new Cli())->getVersion(), // Accede a la constante de la versión
            'Commands' => [
                'serve' => 'Inicia el servidor de desarrollo',
                'make:component' => 'Crea un nuevo componente',
                'make:view' => 'Crea una nueva plantilla',
                'help' => 'Muestra la ayuda'
            ],
        ];

        View::render('main/home', $data);
    }

    public function pruebas()
    {

        $usuarios = new UsuarioModel;
        $all = $usuarios
            ->select('nombre')
            ->where('nombre', 'LIKE', 'A%')
            ->orderBy('nombre', 'ASC')
            ->limit(2)
            ->get();

        View::render('main/pruebas', compact('all'));
    }
}
