<?php

class ProdutoHistoricoController
{
    private $produtoHistoricoService;

    public function __construct(ProdutoHistoricoService $produtoHistoricoService)
    {
        $this->produtoHistoricoService = $produtoHistoricoService;
    }

    public function listar($id_produto): array
    {

        $registrosPorPagina = 8;
        $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

        try {
            $dadosPaginados = $this->produtoHistoricoService->getHistoricoProduto($id_produto, $paginaAtual, $registrosPorPagina);

            return [
                'listaProdutoHistorico' => $dadosPaginados['produtoHistorico'],
                'pagina_atual' => $dadosPaginados['pagina_atual'],
                'total_paginas' => $dadosPaginados['total_paginas'],
                'historico_por_pagina' => $registrosPorPagina,
                'total_historico' => $dadosPaginados['total_historico']
            ];

        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao listar o histórico do produto: ' . $e->getMessage()
            ];

        }
    }
}
