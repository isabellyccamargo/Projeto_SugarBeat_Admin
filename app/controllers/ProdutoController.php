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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 2. Se for POST, chama o método de salvamento
            $this->salvar($data); // Passa os dados iniciais (categorias) para re-renderizar em caso de erro
        } else {
            // Se for GET, apenas renderiza o formulário
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
        }
    }

    /**
     * Lógica de salvamento e validação do produto, incluindo upload de imagem.
     * @param array $data Dados iniciais para re-renderização (ex: lista de categorias).
     */
    private function salvar($data)
    {
        // VARIÁVEIS PARA LÓGICA DE UPLOAD
        $caminhoImagem = null;
        $uploadErro = null;

        // --- 1. LÓGICA DE UPLOAD DE ARQUIVO (Simulada para ambiente virtual) ---
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $arquivo = $_FILES['imagem'];

            // --- PASSO 1: Verificação e Validação Simplificada ---
            $nomeOriginal = basename($arquivo['name']);
            $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

            // Permite APENAS jpg e jpeg (alterado conforme solicitação do usuário)
            $tiposPermitidos = ['jpg']; 
            if (!in_array($extensao, $tiposPermitidos)) {
                $uploadErro = "Tipo de arquivo não permitido ({$extensao}). Somente arquivos JPG e JPEG são aceitos."; // Mensagem atualizada
            }

            // --- PASSO 2: Geração de Nome Único e Simulação de Movimentação ---
            if (!$uploadErro) {
                $nomeUnico = uniqid("prod_", true) . '.' . $extensao;

                /* * NOTA: Em um ambiente real com acesso ao sistema de arquivos:
                 * $diretorio = 'caminho/do/seu/uploads/';
                 * move_uploaded_file($arquivo['tmp_name'], $diretorio . $nomeUnico);
                 */

                // SIMULAÇÃO: Apenas salva o nome único para persistência no banco
                $caminhoImagem = $nomeUnico; 
            }
        } 
        
        // --- 2. TRATAMENTO DE ERRO DE UPLOAD ---
        if ($uploadErro) {
            // Cria um objeto Produto para re-popular o formulário
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
            
            // Re-renderiza a View, mantendo as categorias e o produto preenchido
            $data['produto_com_erro'] = $produtoComErro;
            View::renderWithLayout('produto/CadastroProdutoView', 'config/AppLayout', $data);
            exit();
        }


        // --- 3. COLETA E PREPARAÇÃO DOS DADOS RESTANTES ---
        $dados = [
            'nome' => $_POST['nome'] ?? null,
            'preco' => $_POST['preco'] ?? null,
            'id_categoria' => $_POST['categoria'] ?? null, // Use 'categoria' como nome do campo
            'estoque' => $_POST['estoque'] ?? null,
            'ativo' => $_POST['ativo'] ?? '0', 
            'imagem' => $caminhoImagem // <<-- AGORA USA O CAMINHO GERADO NO UPLOAD
        ];

        // --- 4. INSTANCIA E PREENCHE O MODEL PRODUTO ---
        $produto = new Produto();
        $produto->setNome($dados['nome']);
        $produto->setPreco($dados['preco']);
        $produto->setIdCategoria($dados['id_categoria']);
        $produto->setEstoque($dados['estoque']);
        $produto->setAtivo($dados['ativo']);
        $produto->setImagem($dados['imagem']); // Define o nome do arquivo gerado/simulado

        try {
            // --- 5. VALIDAÇÃO E PERSISTÊNCIA (dentro do Service) ---
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
?>
