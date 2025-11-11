<?php

class ProdutoHistoricoControllerFactory
{
    public static function create(): ProdutoHistoricoControllerFactory
    {
        $pdo = Connection::connect();
        $historicoRepository = new ProdutoHistoricoRepository($pdo);
        $historicoService = new ProdutoHistoricoService($historicoRepository);
        $produtoRepository = new ProdutoRepository($pdo);
        $produtoService = new ProdutoService($produtoRepository);
        return new ProdutoHistoricoControllerFactory($historicoService, $produtoService);
    }
}
?>