<?php

class ProdutoController
{
    private $produtoService;
    private $categoriaService;

    public function __construct(ProdutoService $produtoService, CategoriaService $categoriaService)
    {
        $this->produtoService = $produtoService;
        $this->categoriaService = $categoriaService;
    }

    public function listar($id = null)
    {
        if ($id) {
            try {
                $produto = $this->produtoService->getProduto($id);

                View::renderWithLayout('produto/ListagemProdutoView', 'config/AppLayout', ['produto' => $produto]);
            } catch (Exception $e) {
                http_response_code(404);
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Produto não encontrado: ' . $e->getMessage()
                ];
                header("Location: /produto");
                exit();
            }
        } else {
            $produtosPorPagina = 8; // <<--- DEFINA AQUI QUANTOS PRODUTOS POR PÁGINA

            // Pega a página atual da URL (Query String ?page=X)
            $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

            // Chama o novo método paginado
            $dadosPaginacao = $this->produtoService->getProdutosPaginados($paginaAtual, $produtosPorPagina);

            $categorias = $this->categoriaService->listarTodasCategorias();

            // Corrige o nome da variável: no Controller original você usou 'produto' na View::render, mas na View
            // você usou 'produtos'. O array 'produtos' deve estar na chave 'produtos'
            $data = [
                'produtos' => $dadosPaginacao['produtos'],
                'pagina_atual' => $dadosPaginacao['pagina_atual'],
                'total_paginas' => $dadosPaginacao['total_paginas'],
                'produtos_por_pagina' => $produtosPorPagina,
                'total_produtos' => $dadosPaginacao['total_produtos'],
                'listaCategorias' => $categorias 
            ];

            // Renderiza a View, passando todos os dados necessários
            View::renderWithLayout('produto/ListagemProdutoView', 'config/AppLayout', $data);
        }
    }

    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->salvar();
        } else {
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout');
        }
    }

    private function salvar()
    {
        try {
            $produto = new Produto();
            $produto->setNome($_POST['nome']);
            $produto->setPreco($_POST['preco']);
            $produto->setIdCategoria($_POST['nome_categoria']);
            $produto->setEstoque($_POST['estoque']);
            $produto->setImagem($_POST['imagem'] ?? null);

            $novoProduto = $this->produtoService->criarNovoProduto($produto);

            $produtoIdFormatado = str_pad($novoProduto->getIdProduto(), 5, '0', STR_PAD_LEFT);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => 'Produto cadastrado com sucesso. <br><br>' . '<span style="font-weight:bold; font-size:20px;">#' . $produtoIdFormatado . '</span>'
            ];

            header("Location: /produto");
            exit();
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao cadastrar produto: ' . $e->getMessage()
            ];

            header("Location: /produto/cadastro");
            exit();
        }
    }

    // public function editar($id)
    // {
    //     try {
    //         $produtoAtual = $this->produtoService->getProduto($id);

    //         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //             $this->atualizar($id, $produtoAtual);
    //         } else {
    //             View::renderWithLayout('produto/EdicaoProdutoView', 'config/AppLayout', ['produto' => $produtoAtual]);
    //         }
    //     } catch (Exception $e) {
    //         http_response_code(404);
    //         $_SESSION['alert_message'] = [
    //             'type' => 'error',
    //             'title' => 'Erro!',
    //             'text' => 'Produto não encontrado: ' . $e->getMessage()
    //         ];
    //         header("Location: /produto");
    //         exit();
    //     }
    // }

    // private function atualizar($id, Produto $produtoAtual)
    // {
    //     try {
    //         $produto = new Produto(
    //             $produtoAtual->getIdProduto(),
    //             $_POST['nome'] ?? $produtoAtual->getNome(),
    //             $_POST['preco'] ?? $produtoAtual->getPreco(),
    //             $_POST['imagem'] ?? $produtoAtual->getImagem(), 
    //             $_POST['nome_categoria'] ?? $produtoAtual->getIdCategoria(), 
    //             $_POST['estoque'] ?? $produtoAtual->getEstoque() 
    //         );

    //         $this->produtoService->atualizarProduto($produto);

    //         $produtoIdFormatado = str_pad($produtoAtual->getIdProduto(), 5, '0', STR_PAD_LEFT);

    //         $_SESSION['alert_message'] = [
    //             'type' => 'success',
    //             'title' => 'Sucesso!',
    //             'text' => 'Produto atualizado com sucesso. <br><br>' . '<span style="font-weight:bold; font-size:20px;">#' . $produtoIdFormatado . '</span>'
    //         ];
    //     } catch (Exception $e) {

    //         $_SESSION['alert_message'] = [
    //             'type' => 'error',
    //             'title' => 'Erro!',
    //             'text' => 'Erro ao atualizar produto: ' . $e->getMessage()
    //         ];
    //     } finally {

    //         header("Location: /produto/editar/" . $id);
    //         exit();
    //     }
    // }
}
