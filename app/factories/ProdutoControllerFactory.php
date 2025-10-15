<?php

class ProdutoControllerFactory
{
    public static function create(): ProdutoController
    {
        $pdo = Connection::connect(); 
        $produtoRepository = new ProdutoRepository($pdo); 
        $produtoService = new ProdutoService($produtoRepository); 
        return new ProdutoController($produtoService);
    }
}