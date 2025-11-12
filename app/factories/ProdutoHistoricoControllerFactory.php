<?php

class ProdutoHistoricoControllerFactory
{
    public static function create(): ProdutoHistoricoController
    {
        $pdo = Connection::connect();
        $historicoRepository = new ProdutoHistoricoRepository($pdo);
        $historicoService = new ProdutoHistoricoService($historicoRepository);
        
        return new ProdutoHistoricoController($historicoService);
    }
}
?>