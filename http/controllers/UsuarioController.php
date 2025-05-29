<?php

namespace App\Http\Controllers;

use App\Core\Request;

class UsuarioController {
    public function index(){
        echo "Usuarios";
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