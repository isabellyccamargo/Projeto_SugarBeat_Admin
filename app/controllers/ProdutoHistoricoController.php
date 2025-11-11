<?php


class ProdutoHistoricoController
{
    private $historicoService;
    private $produtoService;

    public function __construct(ProdutoHistoricoService $historicoService, ProdutoService $produtoService)
    {
        $this->historicoService = $historicoService;
        $this->produtoService = $produtoService;
    }

    public function listar()
    {

        $id_produto = $_GET['id_produto'] ?? null;


        $historicoProduto = [];
        $nomeProduto = 'N/A';
        $mensagem_erro = null;

        if (!$id_produto || !is_numeric($id_produto)) {
            $mensagem_erro = "ID do produto inválido ou não fornecido.";
            // Renderiza a view com erro e sem dados
            $this->renderView($historicoProduto, $nomeProduto, $mensagem_erro);
            return;
        }

        try {
            $produto = $this->produtoService->getProduto((int)$id_produto);
            if ($produto) {
                $nomeProduto = $produto->getNome();
            } else {
                $nomeProduto = 'Produto (ID: ' . $id_produto . ') não encontrado';
            }

            $historicoProduto = $this->historicoService->listarHistoricoPorProduto((int)$id_produto);

            if (empty($historicoProduto)) {
                $mensagem_erro = "Nenhum histórico de alterações encontrado para este produto.";
            }
        } catch (\Exception $e) {
            $mensagem_erro = "Erro ao buscar dados do histórico: " . $e->getMessage();
        }

        $this->renderView($historicoProduto, $nomeProduto, $mensagem_erro);
    }

    private function renderView(array $historicoProduto, string $nomeProduto, ?string $mensagem_erro)
    {
        $data = [
            'historicoProduto' => $historicoProduto,
            'nomeProduto' => $nomeProduto,
            'mensagem_erro' => $mensagem_erro
        ];
        extract($data);
        include __DIR__ . '/../views/listagemHistoricoProduto.php';
    }
}
