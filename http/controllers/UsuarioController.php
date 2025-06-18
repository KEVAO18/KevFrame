<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\View;

class UsuarioController {
    public function index(){
        echo "Usuarios";
    }

    public function iniciar(){
        View::render('componentes/usuarios/login');
    }
    
    public function registro(){
        View::render('componentes/usuarios/register');
    }

    public function cerrar(){
        
    }

    public function perfil(Request $request){
        echo "Perfil de usuario: " . $request->get('user_id');
    }

    public function configuracion(){
        echo "Configuracion de usuario";
    }

    public function historial_compras(){
        echo "Historial de compras de usuario";
    }

    public function pedidos(){
        echo "Pedidos de usuario";
    }

    public function pedido($id){
        echo "Pedido de usuario: ". $id;
    }
}