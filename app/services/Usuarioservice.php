<?php

class UsuarioService
{
    private $usuarioRepository;

    public function __construct(IUsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function criarNovoUsuario(Usuario $usuario): Usuario
    {
        $this->validarDadosComuns($usuario, true);

        if (empty($usuario->getSenha())) {
            throw new Exception("A senha é obrigatória para novos usuários.");
        }

        $usuario->setSenha(password_hash($usuario->getSenha(), PASSWORD_DEFAULT));

        return $this->usuarioRepository->save($usuario);
    }

    public function atualizarUsuario(Usuario $usuario): Usuario
    {
        if (empty($usuario->getIdUsuario())) {
            throw new Exception("ID do usuário é obrigatório para atualização.");
        }

        $this->validarDadosComuns($usuario, false);

        $existente = $this->usuarioRepository->getById($usuario->getIdUsuario());
        if (!$existente) {
            throw new Exception("Usuário não encontrado para atualização.");
        }

        $novaSenha = $usuario->getSenha();
        if (!empty($novaSenha)) {
            if (strlen($novaSenha) < 6) {
                throw new Exception("Senha deve ter ao menos 6 caracteres.");
            }
            $usuario->setSenha(password_hash($novaSenha, PASSWORD_DEFAULT));
        } else {
            $usuario->setSenha($existente->getSenha());
        }

        $this->usuarioRepository->update($usuario);

        return $this->usuarioRepository->getById($usuario->getIdUsuario());
    }

    public function autenticarUsuario(string $email, string $senha): Usuario
    {
        $usuario = $this->usuarioRepository->getByEmail($email);
        if (!$usuario || !password_verify($senha, $usuario->getSenha())) {
            throw new Exception("E-mail ou senha inválidos.");
        }
        return $usuario;
    }

    public function getUsuario($id): Usuario
    {
        $usuario = $this->usuarioRepository->getById($id);

        if (!$usuario) {
            throw new Exception("Usuário com ID $id não encontrado.");
        }
        return $usuario;
    }

    public function listarUsuariosComFiltro(?string $adminStatus = null): array
    {
        return $this->usuarioRepository->getAll($adminStatus);
    }

    public function getUsuariosPaginados(int $paginaAtual, int $usuariosPorPagina, ?string $adminFilter): array
    {
        $totalUsuarios = $this->usuarioRepository->countAll($adminFilter);
        $totalPaginas = $usuariosPorPagina > 0 ? ceil($totalUsuarios / $usuariosPorPagina) : 1;
        $paginaAtual = max(1, min((int)$paginaAtual, $totalPaginas));
        $offset = ($paginaAtual - 1) * $usuariosPorPagina;

        $usuarios = $this->usuarioRepository->getPaginated($usuariosPorPagina, $offset, $adminFilter);

        return [
            'usuarios' => $usuarios,
            'pagina_atual' => $paginaAtual,
            'total_paginas' => (int)$totalPaginas,
            'total_usuarios' => $totalUsuarios
        ];
    }

    private function validarDadosComuns(Usuario $usuario, bool $isNew): void
    {
        if (empty(trim($usuario->getNome()))) {
            throw new Exception("O nome do usuário é obrigatório.");
        }

        $emailRaw = $usuario->getEmail();
        if (empty($emailRaw)) {
            throw new Exception("O e-mail é obrigatório.");
        }

        $email = strtolower(trim($emailRaw));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido.");
        }
        $usuario->setEmail($email);

        $usuarioExistente = $this->usuarioRepository->getByEmail($email);
        if ($usuarioExistente) {
            if ($isNew) {
                throw new Exception("E-mail já cadastrado.");
            } else {
                if ($usuarioExistente->getIdUsuario() !== $usuario->getIdUsuario()) {
                    throw new Exception("E-mail já cadastrado por outro usuário.");
                }
            }
        }

        $admin = strtoupper(trim($usuario->getAdministrador() ?? 'N'));
        if ($admin !== 'S' && $admin !== 'N') {
            throw new Exception("O campo 'administrador' deve ser 'S' ou 'N'.");
        }
        $usuario->setAdministrador($admin);
    }

    public function excluirUsuario(int $id): void
    {
        if (empty($id)) {
            throw new Exception("ID do usuário para exclusão é obrigatório.");
        }
        
        if (isset($_SESSION['user_id']) && (int)$_SESSION['user_id'] === $id) {
            throw new Exception("Você não pode excluir a si mesmo.");
        }

        $excluido = $this->usuarioRepository->delete($id);

        if (!$excluido) {
            throw new Exception("Usuário com ID $id não encontrado para exclusão.");
        }
    }
}
