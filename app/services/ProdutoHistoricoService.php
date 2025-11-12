<?php


class ProdutoHistoricoService
{
    private $produtoHistoricoRepository;

    public function __construct(IProdutoHistoricoRepository $produtoHistoricoRepository)
    {
        $this->produtoHistoricoRepository = $produtoHistoricoRepository;
    }
  
    public function getHistoricoProduto(int $id_produto, int $paginaAtual, int $registrosPorPagina): array
    {
        $totalProdutoHistorico = $this->produtoHistoricoRepository->countHistoricoByProdutoId($id_produto);
        $totalPaginas = $registrosPorPagina > 0 ? ceil($totalProdutoHistorico / $registrosPorPagina) : 1;
        $paginaAtual = max(1, min( (int) $paginaAtual, $totalPaginas));
        $offset = ($paginaAtual - 1) * $registrosPorPagina;

        $produtoHistorico = $this->produtoHistoricoRepository->getHistoricoByProdutoId($id_produto, $registrosPorPagina, $offset);

        return [
            'produtoHistorico' => $produtoHistorico,
            'pagina_atual' => $paginaAtual,
            'total_paginas' => (int)$totalPaginas,
            'total_historico' => $totalProdutoHistorico
        ];
    }
    
}