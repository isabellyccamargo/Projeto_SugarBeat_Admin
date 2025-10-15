<?php

class UsuarioControllerFactory
{

    public static function create(): UsuarioController
    {

        $pdo = Connection::connect(); 
        $usuarioRepository = new UsuarioRepository($pdo); 
        $usuarioService = new UsuarioService($usuarioRepository); 
        return new UsuarioController($usuarioService);
    }
}