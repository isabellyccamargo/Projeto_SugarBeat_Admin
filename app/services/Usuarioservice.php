<?php

class UsuarioService
{
    private $usuarioRepository;

    public function __construct(IUsuarioRepository $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }


    public function listarUsuariosComFiltro(?string $adminStatus = null): array
    {
        return $this->usuarioRepository->getAll($adminStatus);
    }

    public function getUsuario($id): Usuario
    {
        $usuario = $this->usuarioRepository->getById($id);

        if (!$usuario) {
            throw new Exception("Usuário com ID $id não encontrado.");
        }
        return $usuario;
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

        if (!empty($usuario->getSenha())) {
            $usuario->setSenha(password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
        } else {

            $usuario->setSenha(null);
        }

        return $this->usuarioRepository->update($usuario);
    }

    public function deletarUsuario($id): bool
    {
        $deletado = $this->usuarioRepository->delete($id);

        if (!$deletado) {
            throw new Exception("Falha ao deletar usuário. O ID pode não existir.");
        }

        return $deletado;
    }

    public function autenticarUsuario(string $email, string $senha)
    {
        $usuario = $this->usuarioRepository->getByEmail($email);

        if (!$usuario || !password_verify($senha, $usuario->getSenha())) {
            throw new Exception("E-mail ou senha inválidos.");
        }

        return $usuario;
    }

    private function validarDadosComuns(Usuario $usuario, bool $isNew = false)
    {
        if (empty($usuario->getNome())) {
            throw new Exception("O nome do usuário é obrigatório.");
        }

        $email = strtolower(trim($usuario->getEmail()));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("E-mail inválido.");
        }
        $usuario->setEmail($email);


        $usuarioExistente = $this->usuarioRepository->getByEmail($email);
        if ($usuarioExistente && ($isNew || $usuarioExistente->getIdUsuario() !== $usuario->getIdUsuario())) {
            throw new Exception("E-mail já cadastrado.");
        }

        $admin = strtoupper($usuario->getAdministrador());
        if ($admin !== 'S' && $admin !== 'N') {
            throw new Exception("O campo 'administrador' deve ser 'S' ou 'N'.");
        }
        $usuario->setAdministrador($admin);
    }

    public function getUsuariosPaginados(int $paginaAtual, int $usuariosPorPagina, ?string $adminFilter): array
    {
        // O método countAll no Repository deve aceitar o filtro para contar apenas os usuários relevantes.
        $totalUsuarios = $this->usuarioRepository->countAll($adminFilter);
        // 2. Calcula o total de páginas. Se não houver usuários, o total é 1.
        $totalPaginas = $usuariosPorPagina > 0 ? ceil($totalUsuarios / $usuariosPorPagina) : 1;
        // 3. Garante que a página atual é um valor válido (entre 1 e totalPaginas)
        $paginaAtual = max(1, min((int)$paginaAtual, $totalPaginas));
        // 4. Calcula o offset (o número de itens que o banco de dados deve pular)
        $offset = ($paginaAtual - 1) * $usuariosPorPagina;
        // 5. Busca a lista de usuários da página, aplicando o LIMIT, OFFSET e o FILTRO.
        $usuarios = $this->usuarioRepository->getPaginated($usuariosPorPagina, $offset, $adminFilter);

        // 6. Retorna um array com todos os metadados e a lista para o Controller
        return [
            'usuarios' => $usuarios,
            'pagina_atual' => $paginaAtual,
            'total_paginas' => (int) $totalPaginas,
            'total_usuarios' => $totalUsuarios
        ];
    }
}
