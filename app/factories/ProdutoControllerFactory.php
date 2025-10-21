<?php

class ProdutoControllerFactory
{
    public static function create(): ProdutoController
    {
        $pdo = Connection::connect(); 
        $produtoRepository = new ProdutoRepository($pdo); 
        $categoriaRepository = new CategoriaRepository($pdo); 
        $produtoService = new ProdutoService($produtoRepository); 
        $categoriaService = new CategoriaService($categoriaRepository); 
        return new ProdutoController($produtoService, $categoriaService);
    }
}