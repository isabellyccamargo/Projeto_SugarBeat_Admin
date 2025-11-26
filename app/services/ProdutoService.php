    <?php

    class ProdutoService
    {
        private $produtoRepository;

        public function __construct(IProdutoRepository $produtoRepository)
        {
            $this->produtoRepository = $produtoRepository;

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        }

        public function salvar(Produto $produto): Produto
        {

            if (empty($produto->getNome())) {
                throw new Exception("O nome do produto é obrigatório.");
            }
            if (!is_numeric($produto->getPreco()) || $produto->getPreco() < 0) {
                throw new Exception("O preço do produto deve ser um valor numérico positivo.");
            }
            if (empty($produto->getIdCategoria())) {
                throw new Exception("A categoria do produto é obrigatória.");
            }
            if (!is_numeric($produto->getEstoque()) || $produto->getEstoque() < 0) {
                throw new Exception("O estoque deve ser um número inteiro não negativo.");
            }

            if (empty($produto->getIdProduto()))
                return $this->produtoRepository->save($produto);
            else {
                return $this->produtoRepository->update($produto);
            }
        }

        public function getProduto($id): Produto
        {
            $produto = $this->produtoRepository->getById($id);

            if (!$produto) {
                throw new Exception("Produto com ID $id não encontrado.");
            }
            return $produto;
        }


        public function listarTodosProdutos(): array
        {
            return $this->produtoRepository->getAll();
        }

        public function atualizarProduto(Produto $produto): bool
        {
            if (!is_numeric($produto->getEstoque()) || $produto->getEstoque() < 0) {
                throw new Exception("O estoque deve ser um número inteiro não negativo ao atualizar.");
            }
            // Você poderia adicionar validações aqui antes de chamar o update do repositório
            return $this->produtoRepository->update($produto);
        }

        public function getProdutosPaginados(int $paginaAtual, int $produtosPorPagina, ?int $categoriaId = null): array
        {
            $totalProdutos = $this->produtoRepository->countAll($categoriaId);

            // Calcula o total de páginas
            $totalPaginas = $produtosPorPagina > 0 ? ceil($totalProdutos / $produtosPorPagina) : 1;

            // Garante que a página atual é válida
            $paginaAtual = max(1, min((int)$paginaAtual, $totalPaginas));

            // Calcula o offset
            $offset = ($paginaAtual - 1) * $produtosPorPagina;

            // Busca a lista de produtos da página
            $produtos = $this->produtoRepository->getPaginated($produtosPorPagina, $offset, $categoriaId);

            // Retorna um array com tudo o que o Controller precisa
            return [
                'produtos' => $produtos,
                'pagina_atual' => $paginaAtual,
                'total_paginas' => (int) $totalPaginas,
                'total_produtos' => $totalProdutos,
                'categoria_id_selecionada' => $categoriaId
            ];
        }

        public function deleteProduto(int $id): void
        {
            $produto = $this->getProduto($id);
            $caminhoImagem = $produto->getImagem();

            if ($this->produtoRepository->hasAssociatedPedidos($id)) {
                throw new Exception("O produto **não pode ser excluído** pois já está associado a pedidos históricos. Para manter a integridade do histórico de vendas, use a função de Inativação (ativo = 0).");
            }

            if (!$this->produtoRepository->deleteHistoricoByProdutoId($id)) {
                error_log("Aviso: Falha ao deletar registros históricos do produto ID: " . $id);
            }

            if (!$this->produtoRepository->delete($id)) {
                throw new Exception("Falha ao deletar o produto no banco de dados.");
            }

            if (!empty($caminhoImagem)) {
                $diretorioRaiz = dirname(dirname(dirname(__DIR__)));
                $caminhoAbsoluto = $diretorioRaiz . $caminhoImagem;

                if (file_exists($caminhoAbsoluto)) {
                    if (!unlink($caminhoAbsoluto)) {
                        error_log("Aviso: Falha ao deletar o arquivo de imagem: " . $caminhoAbsoluto);
                    }
                }
            }
        }
    }
