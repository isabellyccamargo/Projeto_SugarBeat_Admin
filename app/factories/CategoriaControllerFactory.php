<?php

class CategoriaControllerFactory
{

    public static function create(): CategoriaController
    {

        $pdo = Connection::connect(); 
        $categoriaRepository = new CategoriaRepository($pdo); 
        $categoriaService = new CategoriaService($categoriaRepository); 
        return new CategoriaController($categoriaService);
    }
}