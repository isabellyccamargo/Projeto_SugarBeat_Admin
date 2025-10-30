<?php

class UsuarioController
{
    private $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
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
        // Se enviou o formulário
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->salvar();
            exit();
        }

        // Se for edição
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
            // Modo cadastro novo
            View::renderWithLayout('usuario/CadastroUsuarioView', 'config/AppLayout');
        }
    }

    private function salvar()
    {
        $usuarioId = $_POST['id'] ?? null;

        try {
            // Validação básica
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = trim($_POST['senha'] ?? '');
            $administrador = $_POST['administrador'] ?? 'N';

            if (empty($nome) || empty($email) || (!$usuarioId && empty($senha))) {
                throw new Exception("Preencha todos os campos obrigatórios.");
            }

            // Cria/Atualiza o objeto usuário
            $usuario = new Usuario(
                $usuarioId,
                $nome,
                $email,
                $senha,
                $administrador
            );

            // Decide se é cadastro novo ou atualização
            if ($usuarioId) {
                $this->usuarioService->atualizarUsuario($usuario);
                $_SESSION['alert_message'] = [
                    'type' => 'success',
                    'title' => 'Sucesso!',
                    'text' => "Usuário <strong>{$usuario->getNome()}</strong> atualizado com sucesso!"
                ];
            } else {
                $novoUsuario = $this->usuarioService->criarNovoUsuario($usuario);
                $_SESSION['alert_message'] = [
                    'type' => 'success',
                    'title' => 'Sucesso!',
                    'text' => "Usuário <strong>{$novoUsuario->getNome()}</strong> cadastrado com sucesso!"
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

            // Recarrega o formulário mantendo os dados
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
}
