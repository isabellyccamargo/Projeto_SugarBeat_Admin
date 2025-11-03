<?php

class AuthController {
    
    // Rota: /login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $viewPath = 'login/index';

            // AQUI iria a validação de usuário e senha no banco de dados
            $email = $_POST["email"] ?? '';
            $senha_digitada = $_POST["password"] ?? '';

            if (empty($email) || empty($senha_digitada)) {
                $this->showLoginError("E-mail e senha são obrigatórios.", $viewPath, $email);
            }

            if (empty($email) || empty($senha_digitada)) {
                $this->showLoginError("E-mail e senha são obrigatórios.", $viewPath, $email);
            }

            // 1. Instanciação e Conexão (depende de Connection.php e UsuarioRepository.php)
            $pdo = Connection::connect();
            $usuarioRepository = new UsuarioRepository($pdo);

            // 2. Busca o usuário pelo e-mail
            $usuario = $usuarioRepository->getByEmail($email);

            // 3. Verifica se o usuário existe E se a senha corresponde ao hash no DB

            $usuarioValido = ($usuario && password_verify($senha_digitada, $usuario->getSenha()));
            
            if ($usuarioValido) {
                $_SESSION['user_logged'] = true;
                // Opcional: Armazenar dados úteis
                $_SESSION['user_id'] = $usuario->getIdUsuario();
                $_SESSION['user_nome'] = $usuario->getNome();
                $_SESSION['is_admin'] = ($usuario->getAdministrador() === 'S');
                header('Location: /sugarbeat_admin/dashboard');
                exit;
            } else {
                $this->showLoginError("E-mail ou senha inválidos.", $viewPath, $email);
                //View::render('login/LoginView', ['error' => 'Credenciais inválidas.']);
                return;
            }
        }
        
        // Exibe a tela de login (GET)
        View::render('login/index');
    }
    
    // Rota: /dashboard
    // Renderiza o conteúdo da DashboardView DENTRO do AppLayout (localizado em app/core/)
    public function dashboard() {
        // CORREÇÃO: LayoutPath deve ser 'core/AppLayout' conforme a nova localização do arquivo.
        View::renderWithLayout('dashboard/index', 'config/AppLayout');
    }

    // Rota: /logout
    public function logout() {
        session_destroy();
        header('Location: /sugarbeat_admin/login');
        exit;
    }

        private function showLoginError(string $message, string $viewPath, string $email = '')
    {
        $data = [
            'error' => $message,
            'email' => $email // Para repreencher o campo
        ];
        View::render($viewPath, $data);
        exit;
    }
}