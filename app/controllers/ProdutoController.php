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
        // 1. Lógica para carregar categorias (GET request)
        $categorias = $this->categoriaService->listarTodasCategorias();
        $data = ['listaCategorias' => $categorias];
        // Verifica se há dados na Query String (modo edição)
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

            // Passa o produto pré-preenchido para a View
            $data['produto_existente'] = $produtoEdicao;

            // Renderiza o formulário no modo edição
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        } else {
            // Se for GET e não houver ID, apenas renderiza o formulário (modo cadastro)
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        }
    }





    /**
     * Lógica de salvamento e validação do produto, incluindo upload de imagem.
     * @param array $data Dados iniciais para re-renderização (ex: lista de categorias).
     */
    private function salvar(array $data)
    {
        // VARIÁVEIS PARA LÓGICA DE UPLOAD
        $caminhoImagem = null;
        $uploadErro = null;

        // NOVIDADE: Identifica se é uma edição
        $produtoId = $_POST['id'] ?? null;
        $imagemAntigaPath = $_POST['imagem_antiga'] ?? null;

       $diretorioRaiz = dirname(dirname(dirname(__DIR__)));;
        $diretorioUpload = $diretorioRaiz . '/fotos/';

        if (!is_dir($diretorioUpload)) {
            // Tenta criar o diretório recursivamente com permissão 0777 (ajuste se necessário)
            if (!mkdir($diretorioUpload, 0777, true)) {
                $uploadErro = "Erro interno: Não foi possível criar o diretório de upload: " . $diretorioUpload;
            }
        }

        // --- LÓGICA DE UPLOAD DE ARQUIVO ---
        if (!$uploadErro && isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            // NOVO ARQUIVO ENVIADO: Processar o upload e substituir

            $arquivo = $_FILES['imagem'];
            // ... (PASSO 1: Verificação e Validação Simplificada) ...
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
            // Cria um objeto Produto para re-popular o formulário
            $produtoComErro = new Produto();
            // ... (Preenche os dados do produtoComErro com $_POST)
            $produtoComErro->setNome($_POST['nome'] ?? null);
            $produtoComErro->setPreco($_POST['preco'] ?? null);
            $produtoComErro->setIdCategoria($_POST['categoria'] ?? null);
            $produtoComErro->setEstoque($_POST['estoque'] ?? null);
            $produtoComErro->setAtivo($_POST['ativo'] ?? '0');

            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro de Upload!',
                'title' => 'Erro de Cadastro/Upload!',
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
            // --- 5. VALIDAÇÃO E PERSISTÊNCIA (dentro do Service) ---
            $novoProduto = $this->produtoService->salvar($produto);

            $produtoIdFormatado = str_pad($novoProduto->getIdProduto(), 5, '0', STR_PAD_LEFT);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => 'Produto cadastrado com sucesso. <br><br>' . '<span style="font-weight:bold; font-size:20px;">#' . $produtoIdFormatado . '</span>'
            ];

            header("Location: /sugarbeat_admin/produto");
            exit();
        } catch (Exception $e) {

            echo "DEBUG: Exceção Capturada! Mensagem: " . $e->getMessage();

            // --- 6. TRATAMENTO DE ERROS DE VALIDAÇÃO/PERSISTÊNCIA ---
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao cadastrar produto: ' . $e->getMessage()
            ];

            // Mantém os dados no formulário em caso de erro. 
            $data['produto_com_erro'] = $produto;
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        }
    }

    
}
