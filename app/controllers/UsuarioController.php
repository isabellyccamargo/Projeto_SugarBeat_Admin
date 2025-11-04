<?php

class UsuarioController
{
    private $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;

        $requestUri = $_SERVER['REQUEST_URI'];

        $erroUri = '/sugarbeat_admin/usuario/erro';

        // se a URL ATUAL for diferente da URL de erro, executa o filtro
        // Usamos strpos para maior compatibilidade
        if (strpos($requestUri, $erroUri) === false) {
            $this->checkAdminAccess();
        }
    }

    public function listar($id = null)
    {
        $adminFilter = $_GET['admin'] ?? null;

        if ($id) {
            try {
                $usuario = $this->usuarioService->getUsuario($id);
                View::renderWithLayout('usuario/DetalheUsuarioView', 'config/AppLayout', ['usuario' => $usuario]);
            } catch (Exception $e) {
                http_response_code(404);
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Usuário não encontrado: ' . $e->getMessage()
                ];
                header("Location: /sugarbeat_admin/usuario");
                exit();
            }
        } else {

            $usuariosPorPagina = 8;
            $paginaAtual = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

            try {
                $dadosPaginacao = $this->usuarioService->getUsuariosPaginados(
                    $paginaAtual,
                    $usuariosPorPagina,
                    $adminFilter
                );

                $data = [
                    'listaUsuarios' => $dadosPaginacao['usuarios'],
                    'pagina_atual' => $dadosPaginacao['pagina_atual'],
                    'total_paginas' => $dadosPaginacao['total_paginas'],
                    'usuarios_por_pagina' => $usuariosPorPagina,
                    'total_usuarios' => $dadosPaginacao['total_usuarios'],
                    'adminFilter' => $adminFilter
                ];

                View::renderWithLayout('usuario/ListagemUsuarioView', 'config/AppLayout', $data);
            } catch (Exception $e) {
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Erro ao listar usuários: ' . $e->getMessage()
                ];
                View::renderWithLayout('usuario/ListagemUsuarioView', 'config/AppLayout', ['listaUsuarios' => []]);
            }
        }
    }

    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->salvar();
            exit();
        }

        $usuarioId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($usuarioId) {
            try {
                $usuario = $this->usuarioService->getUsuario($usuarioId);
                View::renderWithLayout('usuario/CadastroUsuarioView', 'config/AppLayout', ['usuario_existente' => $usuario]);
            } catch (Exception $e) {
                $_SESSION['alert_message'] = [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'text' => 'Usuário não encontrado: ' . $e->getMessage()
                ];
                header("Location: /sugarbeat_admin/usuario");
                exit();
            }
        } else {
            View::renderWithLayout('usuario/CadastroUsuarioView', 'config/AppLayout');
        }
    }

    private function salvar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $usuarioId = !empty($_POST['id']) ? (int)$_POST['id'] : null;

        try {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $administrador = $_POST['administrador'] ?? 'N';

            if (empty($nome) || empty($email) || (!$usuarioId && empty($senha))) {
                throw new Exception("Preencha todos os campos obrigatórios.");
            }

            $usuario = new Usuario(
                $usuarioId,
                $nome,
                $email,
                $senha,
                $administrador
            );


            // Decide se é cadastro novo ou atualização
            if ($usuarioId) {
                $usuario = $this->usuarioService->atualizarUsuario($usuario);

                $_SESSION['alert_message'] = [
                    'type' => 'success',
                    'title' => 'Sucesso!',
                    'text' => "Usuário <strong>{$usuario->getNome()}</strong> atualizado com sucesso!"
                ];

                // verifica se o usuario que esta sendo salvo é o mesmo da sessao
                if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $usuarioId) {

                    // Atualiza o status de admin na sessão
                    $isAdmin = ($usuario->getAdministrador() === 'S');
                    $_SESSION['is_admin'] = $isAdmin;

                    // Se o próprio usuário editado perdeu o admin ( isAdmin === false )
                    if (!$isAdmin) {
                        $_SESSION['alert_message'] = [
                            'type' => 'warning',
                            'title' => 'Atenção!',
                            'text' => 'Seu perfil de administrador foi revogado.'
                        ];
                        header("Location: /sugarbeat_admin/usuario/erro");
                        exit();
                    }
                }
            } else {
                $usuario = $this->usuarioService->criarNovoUsuario($usuario);
                $_SESSION['alert_message'] = [
                    'type' => 'success',
                    'title' => 'Sucesso!',
                    'text' => "Usuário <strong>{$usuario->getNome()}</strong> cadastrado com sucesso!"
                ];
            }

            header("Location: /sugarbeat_admin/usuario");
            exit();
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao salvar usuário: ' . $e->getMessage()
            ];

            $usuarioErro = new Usuario(
                $usuarioId,
                $_POST['nome'] ?? '',
                $_POST['email'] ?? '',
                '',
                $_POST['administrador'] ?? 'N'
            );

            View::renderWithLayout('usuario/CadastroUsuarioView', 'config/AppLayout', [
                'usuario_com_erro' => $usuarioErro
            ]);
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /sugarbeat_admin/login');
        exit;
    }

    private function checkAdminAccess()
    {
        // Verifica se a sessão não existe ou se ela é falsa (diferente de true)
        if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            // 1. Barramento de URL manual (Critério 3)
            // Redireciona para a página de erro
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Acesso Negado',
                'text' => 'Você não tem permissão para acessar esta área.'
            ];
            header("Location: /sugarbeat_admin/usuario/erro");
            exit();
        }
    }

    public function erroAcesso()
    {
        // View::renderWithLayout('usuario/UsuarioNaoAdminView', 'config/AppLayout');

        // Ajuste o nome da View para a que você mencionou: UsuarioErroView.php
        View::renderWithLayout('usuario/UsuarioErroView', 'config/AppLayout');

        // O exit é importante para garantir que nenhuma outra renderização ocorra
        exit;
    }
}
