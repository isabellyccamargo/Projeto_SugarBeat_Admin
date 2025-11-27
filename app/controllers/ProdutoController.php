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

        if ($id !== null && is_numeric($id)) {
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
                header("Location: /sugarbeat_admin/produto");
                exit();
            }
        } else {
            //Decide quantos itens por página
            $produtosPorPagina = 8;

            $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

            $categoriaId = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT) ?: null;

            $dadosPaginacao = $this->produtoService->getProdutosPaginados($paginaAtual, $produtosPorPagina, $categoriaId);

            $categorias = $this->categoriaService->listarTodasCategorias();

            $data = [
                'produtos' => $dadosPaginacao['produtos'],
                'pagina_atual' => $dadosPaginacao['pagina_atual'],
                'total_paginas' => $dadosPaginacao['total_paginas'],
                'produtos_por_pagina' => $produtosPorPagina,
                'total_produtos' => $dadosPaginacao['total_produtos'],
                'listaCategorias' => $categorias
            ];

            View::renderWithLayout('produto/ListagemProdutoView', 'config/AppLayout', $data);
        }
    }

    public function excluir($id = null)
    {
        if ($id === null || !is_numeric($id)) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'ID de produto inválido ou não fornecido.'
            ];
            header("Location: /sugarbeat_admin/produto");
            exit();
        }

        try {
            $this->produtoService->deleteProduto((int)$id);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Produto **#{$id}** excluído com sucesso."
            ];

        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao excluir produto: ' . $e->getMessage()
            ];
        }

        header("Location: /sugarbeat_admin/produto");
        exit();
    }

    public function cadastro()
    {
        $categorias = $this->categoriaService->listarTodasCategorias();
        $data = ['listaCategorias' => $categorias];
        $produtoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->salvar($data);
            exit();
        } else if ($produtoId) {
            $produtoEdicao = new Produto();
            $produtoEdicao->setIdProduto($produtoId);
            $produtoEdicao->setNome($_GET['nome'] ?? null);
            $produtoEdicao->setPreco($_GET['preco'] ?? null);
            $produtoEdicao->setIdCategoria($_GET['categoria'] ?? null);
            $produtoEdicao->setEstoque($_GET['estoque'] ?? null);
            $produtoEdicao->setAtivo($_GET['ativo'] ?? '0');
            $produtoEdicao->setImagem($_GET['imagem_path'] ?? null);

            $data['produto_existente'] = $produtoEdicao;

            $produtoHistoricoController = ProdutoHistoricoControllerFactory::create();
            $produtoHistorico = $produtoHistoricoController->listar($produtoId);

            $data['produto_historico'] = $produtoHistorico;
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        } else {
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        }
    }

    private function salvar(array $data)
    {
        $caminhoImagem = null;
        $uploadErro = null;

        $produtoId = $_POST['id'] ?? null;
        $imagemAntigaPath = $_POST['imagem_antiga'] ?? null;

        $diretorioRaiz = dirname(dirname(dirname(__DIR__)));;
        $diretorioUpload = $diretorioRaiz . '/fotos/';

        if (!is_dir($diretorioUpload)) {
            if (!mkdir($diretorioUpload, 0777, true)) {
                $uploadErro = "Erro interno: Não foi possível criar o diretório de upload: " . $diretorioUpload;
            }
        }
        if (!$uploadErro && isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $arquivo = $_FILES['imagem'];
            $nomeOriginal = basename($arquivo['name']);
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
            $tiposPermitidos = ['jpg', 'jpeg', 'png'];

            if (!in_array($extensao, $tiposPermitidos)) {
                $uploadErro = "Tipo de arquivo não permitido ({$extensao}). Somente arquivos JPG, JPEG e PNG são aceitos.";
            }


            // --- Geração de Nome Único e Movimentação ---
            if (!$uploadErro) {
                $nomeUnico = uniqid("prod_", true) . '.' . $extensao;
                $caminhoCompletoDestino = $diretorioUpload . $nomeUnico;

                if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompletoDestino)) {
                    $prefixoAntigo = '../../../../fotos/';
                    $caminhoImagem = $prefixoAntigo . $nomeUnico;
                    // NOVIDADE: Se a imagem antiga existe e não é nula/placeholder, a deletamos
                    if ($produtoId && $imagemAntigaPath && file_exists($diretorioRaiz . '/' . $imagemAntigaPath)) {
                        unlink($diretorioRaiz . '/' . $imagemAntigaPath);
                    }
                } else {
                    $uploadErro = "Ocorreu um erro ao tentar salvar o novo arquivo no servidor.";
                }
            }
        } else if ($produtoId && $_FILES['imagem']['error'] === UPLOAD_ERR_NO_FILE) {

            $caminhoImagem = $imagemAntigaPath;
        } else if (isset($_POST['remover_imagem']) && $_POST['remover_imagem'] == '1') {

            if ($produtoId && $imagemAntigaPath && file_exists($diretorioRaiz . '/' . $imagemAntigaPath)) {
                unlink($diretorioRaiz . '/' . $imagemAntigaPath);
            }
            $caminhoImagem = null;
        } elseif (!$uploadErro && isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Tratamento de outros erros de upload do PHP (ex: tamanho excedido)
            $uploadErro = "Erro no upload do arquivo (Código: " . $_FILES['imagem']['error'] . ").";
        }


        // --- 2. TRATAMENTO DE ERRO DE UPLOAD ---
        if ($uploadErro) {
            $produtoComErro = new Produto();
            $produtoComErro->setNome($_POST['nome'] ?? null);
            $produtoComErro->setPreco($_POST['preco'] ?? null);
            $produtoComErro->setIdCategoria($_POST['categoria'] ?? null);
            $produtoComErro->setEstoque($_POST['estoque'] ?? null);
            $produtoComErro->setAtivo($_POST['ativo'] ?? '0');

            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro de Upload!',
                'text' => $uploadErro
            ];

            $data['produto_com_erro'] = $produtoComErro;
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
            exit();
        }

        // --- 3. COLETA E PREPARAÇÃO DOS DADOS RESTANTES ---
        $dados = [
            'id' => $produtoId,
            'nome' => $_POST['nome'] ?? null,
            'preco' => $_POST['preco'] ?? null,
            'id_categoria' => $_POST['categoria'] ?? null,
            'estoque' => $_POST['estoque'] ?? null,
            'ativo' => $_POST['ativo'] ?? '0',
            'imagem' => $caminhoImagem
        ];

        // --- 4. INSTANCIA E PREENCHE O MODEL PRODUTO ---
        $produto = new Produto();
        if ($dados['id']) {
            $produto->setIdProduto($dados['id']); // Seta o ID para o Service saber que é UPDATE
        }
        $produto->setNome($dados['nome']);
        $produto->setPreco($dados['preco']);
        $produto->setIdCategoria($dados['id_categoria']);
        $produto->setEstoque($dados['estoque']);
        $produto->setAtivo($dados['ativo']);
        $produto->setImagem($dados['imagem']);

        try {
            $novoProduto = $this->produtoService->salvar($produto);

            $produtoIdFormatado = str_pad($novoProduto->getIdProduto(), 5, '0', STR_PAD_LEFT);
            $acao = $produtoId ? 'atualizado' : 'cadastrado';

            // O uso de tags HTML no 'text' é para o Swal.fire que usa 'html'
            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Produto {$acao} com sucesso. <br><br>Código: <strong>#{$produtoIdFormatado}</strong>"
            ];

            // NOVO: Redireciona para a listagem. O SweetAlert aparecerá na página de destino!
            header("Location: /sugarbeat_admin/produto");
            exit();
        } catch (Exception $e) {

            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao salvar produto: ' . $e->getMessage()
            ];

            $data['produto_com_erro'] = $produto;
            // RENDERIZA A VIEW: O SweetAlert aparece na tela de Cadastro/Edição
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        }
    }
}
