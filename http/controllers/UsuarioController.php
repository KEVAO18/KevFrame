<?php

namespace App\Http\Controllers;

// clases core
use App\Core\Request;
use App\Core\SessionManager;
use App\Core\View;

// clases handlers
use App\Http\Handlers\CredencialesHandler;
use App\Http\Handlers\DireccionesEnvioHandler;
use App\Http\Handlers\ProcesosAlmacenadosHandler;
use App\Http\Handlers\UsuariosHandler;

// clases modelos
use App\Models\Usuario;

class UsuarioController
{

    public function iniciar()
    {
        View::render('componentes/usuarios/login');
    }

    public function iniciar_post()
    {

        // instanciacion del handler procesos almacenados
        $proc_almacenados = new ProcesosAlmacenadosHandler();

        // instancia del modelo de usuarios
        $usuario = $proc_almacenados->login($_POST['email']);

        if (
            $usuario == null ||
            !password_verify($_POST['password'] . $usuario->getSalt(), $usuario->getPass())
        ) {
            header('Location: ' . $_ENV['APP_BASE_URL'] . 'iniciar');
        }

        $credenciales  = new CredencialesHandler();
        $credenciales  = $credenciales->getByUser($usuario->getDni());

        $coockies = new SessionManager();
        $coockies->start();
        $coockies->set('user_id', $usuario->getDni());
        $coockies->set('user_name', $usuario->getFullname());
        $coockies->set('user_email', $usuario->getEmail());
        $coockies->set('user_rol', $credenciales->getId());

        header('Location: ' . $_ENV['APP_BASE_URL']);
    }

    public function registro()
    {
        View::render('componentes/usuarios/register');
    }

    public function registro_post()
    {

        // validacion de clausulas
        if (
            !isset($_POST['tyc']) ||
            !isset($_POST['privPoli']) ||
            !isset($_POST['dni']) ||
            !isset($_POST['fullname']) ||
            !isset($_POST['email']) ||
            !isset($_POST['repeat_email']) ||
            !isset($_POST['password']) ||
            !isset($_POST['repeat_pass'])
        ) header('Location: ' . $_ENV['APP_BASE_URL'] . 'registro');

        // instanciacion del handler de usuarios
        $user_handler = new UsuariosHandler();

        // generacion del salt
        $salt = bin2hex(random_bytes(16));

        // generacion del hash
        $hash = password_hash($_POST['password'] . $salt, PASSWORD_BCRYPT, ['cost' => 10]);

        // instanciacion del modelo de usuarios
        $usuario = new Usuario(
            $_POST['dni'],
            $_POST['fullname'],
            $_POST['email'],
            $hash,
            $salt,
            true
        );

        // guardado del usuario
        $user_handler->create($usuario);

        // redireccion al login
        header('Location: ' . $_ENV['APP_BASE_URL'] . 'iniciar');
    }

    public function cerrar()
    {

        $coockies = new SessionManager();
        $coockies->start();
        $coockies->destroy();

        header('Location: ' . $_ENV['APP_BASE_URL']);
    }

    public function perfil()
    {
        $sm = new SessionManager();
        $sm->start();

        if (
            !$sm->has('user_id') ||
            !$sm->has('user_name') ||
            !$sm->has('user_email') ||
            !$sm->has('user_rol')
        ) header('Location: ' . $_ENV['APP_BASE_URL']);

        $uh = new UsuariosHandler();
        $usuario = $uh->getById($sm->get('user_id'));

        $direcciones = new DireccionesEnvioHandler();
        $direcciones = $direcciones->getByUser($usuario->getDni());

        View::render('componentes/usuarios/perfil', compact('usuario', 'direcciones'));
    }

    public function facturas()
    {
        echo "Historial de compras de usuario";
    }

    public function pedidos()
    {
        echo "Pedidos de usuario";
    }

    public function pedido($id)
    {
        echo "Pedido de usuario: " . $id;
    }
}
