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

    public function criarNovoProduto(Produto $produto): Produto
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
        
        return $this->produtoRepository->save($produto);
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

    public function deletarProduto($id): bool
    {
        // Você poderia adicionar lógica de verificação (ex: se o produto está em pedidos) antes de deletar
        return $this->produtoRepository->delete($id);
    }

   
    public function adicionarAoCarrinho($idProduto): array
    {
        // 1. Busca o produto
        try {
            $produto = $this->getProduto($idProduto);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Produto não encontrado.'];
        }

        // 2. Inicializa o carrinho se não existir
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $encontrado = false;
        $quantidadeAtualNoCarrinho = 0;
        
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            // Verifica se o produto já está no carrinho
            if ($item['id'] == $idProduto) {
                $quantidadeAtualNoCarrinho = $item['quantidade'];
                
                // NOVO: Verifica se há estoque suficiente para adicionar mais um
                if ($produto->getEstoque() <= $quantidadeAtualNoCarrinho) {
                    return ['success' => false, 'message' => 'Estoque insuficiente para adicionar mais uma unidade de ' . $produto->getNome() . '.'];
                }
                
                // Aumenta a quantidade
                $_SESSION['carrinho'][$chave]['quantidade']++;
                $encontrado = true;
                break;
            }
        }

        // 3. Se não encontrado, adiciona um novo item (Verifica estoque para 1 unidade)
        if (!$encontrado) {
            // NOVO: Verifica se há pelo menos 1 unidade em estoque
            if ($produto->getEstoque() < 1) {
                 return ['success' => false, 'message' => 'Produto sem estoque (' . $produto->getNome() . ').'];
            }
            
            $novo_item = [
                'id'         => $produto->getIdProduto(),
                'nome'       => $produto->getNome(),
                'imagem'     => $produto->getImagem(),
                'preco'      => $produto->getPreco(),
                'quantidade' => 1
            ];
            $_SESSION['carrinho'][] = $novo_item;
        }
    

        return ['success' => true, 'message' => 'Produto adicionado ao carrinho com sucesso!'];
    }

    public function getCarrinho(): array
    {
        return $_SESSION['carrinho'] ?? [];
    }


    public function getQuantidadeTotalCarrinho(): int
    {
        $total = 0;
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $total += $item['quantidade'];
            }
        }
        return $total;
    }

    public function getValorTotalCarrinho(): float
    {
        $total = 0.0;
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $total += $item['preco'] * $item['quantidade'];
            }
        }
        return $total;
    }

    public function removerDoCarrinho($idProduto): array
    {
        if (isset($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $chave => $item) {
                if ($item['id'] == $idProduto) {
                    unset($_SESSION['carrinho'][$chave]);
                    // Reindexa o array para evitar chaves não sequenciais
                    $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); 
                    return ['success' => true, 'message' => 'Produto removido do carrinho.'];
                }
            }
        }
        return ['success' => false, 'message' => 'Produto não encontrado no carrinho.'];
    }


    public function atualizarQuantidadeCarrinho($idProduto, $novaQuantidade): array
    {
        if (!isset($_SESSION['carrinho'])) {
            return ['success' => false, 'message' => 'Carrinho não encontrado.'];
        }
        
        // Garante que a quantidade é um número inteiro válido (e >= 1)
        $novaQuantidade = max(1, (int)$novaQuantidade);
        
        // Busca o produto (necessário para checar estoque)
        try {
            $produto = $this->getProduto($idProduto);
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Produto não encontrado no sistema.'];
        }

        // NOVO: Checagem de estoque
        if ($novaQuantidade > $produto->getEstoque()) {
            return ['success' => false, 'message' => 'Estoque insuficiente. Máximo disponível: ' . $produto->getEstoque() . '.'];
        }
        
        $encontrado = false;
        foreach ($_SESSION['carrinho'] as $chave => $item) {
            if ($item['id'] == $idProduto) {
                $_SESSION['carrinho'][$chave]['quantidade'] = $novaQuantidade;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            return ['success' => false, 'message' => 'Produto não encontrado no carrinho.'];
        }

        return ['success' => true, 'message' => 'Quantidade atualizada com sucesso.'];
    }

    public function getProdutosPaginados(int $paginaAtual, int $produtosPorPagina): array
    {
        $totalProdutos = $this->produtoRepository->countAll();
        
        // Calcula o total de páginas
        $totalPaginas = $produtosPorPagina > 0 ? ceil($totalProdutos / $produtosPorPagina) : 1;
        
        // Garante que a página atual é válida
        $paginaAtual = max(1, min((int)$paginaAtual, $totalPaginas));
        
        // Calcula o OFFSET
        $offset = ($paginaAtual - 1) * $produtosPorPagina;

        // Busca a lista de produtos da página
        $produtos = $this->produtoRepository->getPaginated($produtosPorPagina, $offset);

        // Retorna um array com tudo o que o Controller precisa
        return [
            'produtos' => $produtos,
            'pagina_atual' => $paginaAtual,
            'total_paginas' => (int) $totalPaginas,
            'total_produtos' => $totalProdutos
        ];
    }
}