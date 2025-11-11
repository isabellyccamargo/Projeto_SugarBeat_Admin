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

    // Método principal: Verifica POST antes de renderizar (como você solicitou)
    public function listarHistoricoAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Se for POST, processa o formulário de salvamento (Ex: Adicionar nota de histórico)
            $this->salvar();
            // Geralmente, o salvar() deve redirecionar após o POST
            return;
            
        } else {
            
            // Se for GET (ou qualquer outro método), exibe a lista de histórico
            $id_produto = $_GET['id_produto'] ?? null;

            $historicoProduto = [];
            $nomeProduto = 'N/A';
            $mensagem_erro = null;

            if (!$id_produto || !is_numeric($id_produto)) {
                $mensagem_erro = "ID do produto inválido ou não fornecido.";
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

            // A chamada para a View fica no final do bloco GET/else
            $this->renderView($historicoProduto, $nomeProduto, $mensagem_erro);
        }
    }
    
    /**
     * Método privado para lidar com a submissão de formulário POST (se necessário).
     */
    private function salvar()
    {
        // 🚨 Coloque aqui a sua lógica de salvamento.
        // Se você não tiver um formulário POST na página de histórico (apenas lista), 
        // você pode adicionar uma mensagem de erro ou redirecionar para evitar 404.
        
        // Exemplo de redirecionamento para o próprio histórico após salvar algo
        // header('Location: /sugarbeat_admin/produto/historico?id_produto=' . ($_POST['id_produto'] ?? ''));
        // exit;
    }

    private function renderView(array $historicoProduto, string $nomeProduto, ?string $mensagem_erro)
    {
        $data = [
            'historicoProduto' => $historicoProduto,
            'nomeProduto' => $nomeProduto,
            'mensagem_erro' => $mensagem_erro
        ];
        
        // Assumindo que você usa a classe View ou um include direto com extract
        extract($data);
         View::renderWithLayout('produto/ProdutoHistoricoView', 'config/AppLayout');
    }
}