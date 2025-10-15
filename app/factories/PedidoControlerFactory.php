<?php

class PedidoControllerFactory
{
    public static function create(): PedidoController
    {
        $pdo = Connection::connect(); 
        $pedidoRepository = new PedidoRepository($pdo); 
        $itemPedidoRepository = new ItemPedidoRepository($pdo); 
        $produtoRepository = new ProdutoRepository($pdo);
        $produtoService = new ProdutoService($produtoRepository);
        $pedidoService = new PedidoService($pedidoRepository, $itemPedidoRepository);
        return new PedidoController($pedidoService);
    }
}