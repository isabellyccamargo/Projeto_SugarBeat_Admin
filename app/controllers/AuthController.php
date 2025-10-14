]<?php

class AuthController {
    
    // Rota: /login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // AQUI iria a validação de usuário e senha no banco de dados
            $usuarioValido = true; // Simulação
            
            if ($usuarioValido) {
                $_SESSION['user_logged'] = true;
                header('Location: /sugarbeat_admin/dashboard');
                exit;
            } else {
                View::render('login/LoginView', ['error' => 'Credenciais inválidas.']);
                return;
            }
        }
        
        // Exibe a tela de login (GET)
        View::render('login/LoginView');
    }

    // Rota: /dashboard
    // Renderiza o conteúdo da DashboardView DENTRO do AppLayout (localizado em app/core/)
    public function dashboard() {
        // CORREÇÃO: LayoutPath deve ser 'core/AppLayout' conforme a nova localização do arquivo.
        View::renderWithLayout('dashboard/DashboardView', 'config/AppLayout');
    }

    // Rota: /logout
    public function logout() {
        session_destroy();
        header('Location: /sugarbeat_admin/login');
        exit;
    }
}