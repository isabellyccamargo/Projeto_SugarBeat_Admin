<?php

class UsuarioController
{
    private $usuarioService;

    public function __construct(UsuarioService $usuarioService)
    {
        $this->usuarioService = $usuarioService;
    }


    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            try {
                $usuario = $this->usuarioService->autenticarUsuario($email, $senha);

                if (!$usuario) {
                    throw new Exception("Erro de autenticação: Objeto Usuário não retornado.");
                }

                $_SESSION['user_id'] = $usuario->getIdUsuario();
                $_SESSION['user_nome'] =  $usuario->getNome(); // Salva o nome real
                $_SESSION['is_admin'] = $usuario->isAdministrador();
                $_SESSION['user_logged'] = true;

                header("Location: /sugarbeat_admin/dashboard");
                exit();
            } catch (Exception $e) {
                $_SESSION['login_error'] = $e->getMessage();
                header("Location: /sugarbeat_admin/login");
                exit();
            }
        } else {

            View::render('usuario/LoginView');
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /sugarbeat_admin/login");
        exit();
    }


    public function listar($id = null)
    {

        if ($id) {
            try {
                $usuario = $this->usuarioService->getUsuario($id); 
                View::renderWithLayout('usuario/DetalheUsuarioView', 'config/AppLayout', ['usuario' => $usuario]);
            } catch (Exception $e) {
                http_response_code(404);
                $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => $e->getMessage()];
                header("Location: /sugarbeat_admin/usuario");
                exit();
            }
        } else {
            try {
                $usuarios = $this->usuarioService->listarTodosUsuarios();

                // Mude 'listaUsuarios' para 'usuario'
                View::renderWithLayout('usuario/ListagemUsuarioView', 'config/AppLayout', ['listaUsuarios' => $usuarios]);
            } catch (Exception $e) {
                $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => 'Erro ao listar usuários: ' . $e->getMessage()];
                View::renderWithLayout('usuario/ListagemUsuarioView', 'config/AppLayout', ['listaUsuarios' => []]);
            }
        }
    }

    public function cadastro()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->salvar();
        } else {
            View::renderWithLayout('usuario/CadastroUsuarioView', 'config/AppLayout');
        }
    }

    private function salvar()
    {
        try {
            $usuario = new Usuario(
                null,
                $_POST['nome'] ?? '',
                $_POST['email'] ?? '',
                $_POST['senha'] ?? '',
                $_POST['administrador'] ?? 'N'
            );

            $novoUsuario = $this->usuarioService->criarNovoUsuario($usuario);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Usuário '{$novoUsuario->getNome()}' cadastrado com sucesso."
            ];

            header("Location: /sugarbeat_admin/usuario");
            exit();
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao cadastrar usuário: ' . $e->getMessage()
            ];

            header("Location: /sugarbeat_admin/usuario/cadastro");
            exit();
        }
    }

    public function editar($id)
    {
        try {
            $usuarioAtual = $this->usuarioService->getUsuario($id);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->atualizar($id, $usuarioAtual);
            } else {
                View::renderWithLayout('usuario/EdicaoUsuarioView', 'config/AppLayout', ['usuario' => $usuarioAtual]);
            }
        } catch (Exception $e) {
            http_response_code(404);
            $_SESSION['alert_message'] = ['type' => 'error', 'title' => 'Erro!', 'text' => $e->getMessage()];
            header("Location: /sugarbeat_admin/usuario");
            exit();
        }
    }

    private function atualizar($id, Usuario $usuarioAtual)
    {
        try {
            $novaSenha = $_POST['senha'] ?? '';

            $usuario = new Usuario(
                $id,
                $_POST['nome'] ?? $usuarioAtual->getNome(),
                $_POST['email'] ?? $usuarioAtual->getEmail(),
                $novaSenha,
                $_POST['administrador'] ?? $usuarioAtual->getAdministrador()
            );

            $this->usuarioService->atualizarUsuario($usuario);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Usuário '{$usuario->getNome()}' atualizado com sucesso."
            ];
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao atualizar usuário: ' . $e->getMessage()
            ];
        } finally {
            header("Location: /sugarbeat_admin/usuario/editar/" . $id);
            exit();
        }
    }

    public function deletar($id)
    {
        try {
            $usuario = $this->usuarioService->getUsuario($id);
            $nome = $usuario->getNome();

            $this->usuarioService->deletarUsuario($id);

            $_SESSION['alert_message'] = [
                'type' => 'success',
                'title' => 'Sucesso!',
                'text' => "Usuário '{$nome}' excluído com sucesso."
            ];
        } catch (Exception $e) {
            $_SESSION['alert_message'] = [
                'type' => 'error',
                'title' => 'Erro!',
                'text' => 'Erro ao deletar usuário: ' . $e->getMessage()
            ];
        } finally {
            header("Location: /sugarbeat_admin/usuario");
            exit();
        }
    }

    public function dashboard()
    {
        // Apenas renderiza a View, confiando que a sessão já está ativa.
        // O AppLayout.php vai ler $_SESSION['user_nome']
        View::renderWithLayout('dashboard/index', 'config/AppLayout');
    }
}
