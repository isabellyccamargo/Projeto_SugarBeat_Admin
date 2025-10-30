<?php

require_once 'IUsuarioRepository.php';

class UsuarioRepository implements IUsuarioRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // ===========================
    // OBTÉM USUÁRIO POR ID
    // ===========================
    public function getById($id): ?Usuario
    {
        $stmt = $this->db->prepare("
            SELECT id_usuario, nome, email, senha, administrador 
            FROM usuario 
            WHERE id_usuario = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Usuario(
            $data['id_usuario'],
            $data['nome'],
            $data['email'],
            $data['senha'],
            $data['administrador']
        );
    }

    // ===========================
    // OBTÉM USUÁRIO POR EMAIL
    // ===========================
    public function getByEmail(string $email): ?Usuario
    {
        $stmt = $this->db->prepare("
            SELECT id_usuario, nome, email, senha, administrador 
            FROM usuario 
            WHERE email = :email
        ");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Usuario(
            $data['id_usuario'],
            $data['nome'],
            $data['email'],
            $data['senha'],
            $data['administrador']
        );
    }

    // ===========================
    // LISTA TODOS OS USUÁRIOS
    // ===========================
    public function getAll(?string $adminStatus = null): array
    {
        $sql = "SELECT id_usuario, nome, email, administrador FROM usuario";
        $params = [];

        if (in_array($adminStatus, ['S', 'N'])) {
            $sql .= " WHERE administrador = :admin";
            $params[':admin'] = $adminStatus;
        }

        $sql .= " ORDER BY nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $usuariosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($usuariosData as $data) {
            $usuarios[] = new Usuario(
                $data['id_usuario'],
                $data['nome'],
                $data['email'],
                null, // senha omitida
                $data['administrador']
            );
        }

        return $usuarios;
    }

    // ===========================
    // SALVA NOVO USUÁRIO
    // ===========================
    public function save(Usuario $usuario): Usuario
    {
        $stmt = $this->db->prepare("
            INSERT INTO usuario (nome, email, senha, administrador)
            VALUES (:nome, :email, :senha, :administrador)
        ");

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':administrador', $usuario->getAdministrador());

        $stmt->execute();
        $usuario->setIdUsuario($this->db->lastInsertId());

        return $usuario;
    }

    // ===========================
    // ATUALIZA USUÁRIO EXISTENTE
    // ===========================
    public function update(Usuario $usuario): Usuario
    {
        $sql = "UPDATE usuario 
                SET nome = :nome, email = :email, administrador = :administrador";

        if (!empty($usuario->getSenha())) {
            $sql .= ", senha = :senha";
        }

        $sql .= " WHERE id_usuario = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':administrador', $usuario->getAdministrador());

        if (!empty($usuario->getSenha())) {
            $stmt->bindValue(':senha', $usuario->getSenha());
        }

        $stmt->bindValue(':id', $usuario->getIdUsuario(), PDO::PARAM_INT);
        $stmt->execute();

        return $usuario;
    }

    // ===========================
    // CONTADOR DE USUÁRIOS
    // ===========================
    public function countAll(?string $adminFilter = null): int
    {
        $sql = "SELECT COUNT(*) FROM usuario";
        $params = [];

        if (in_array($adminFilter, ['S', 'N'])) {
            $sql .= " WHERE administrador = :administrador";
            $params[':administrador'] = $adminFilter;
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // ===========================
    // LISTAGEM PAGINADA
    // ===========================
    public function getPaginated(int $limit, int $offset, ?string $adminFilter = null): array
    {
        $sql = "
            SELECT id_usuario, nome, email, administrador 
            FROM usuario
        ";
        $params = [
            ':limit' => $limit,
            ':offset' => $offset
        ];

        if (in_array($adminFilter, ['S', 'N'])) {
            $sql .= " WHERE administrador = :administrador";
            $params[':administrador'] = $adminFilter;
        }

        $sql .= " ORDER BY id_usuario ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        if (isset($params[':administrador'])) {
            $stmt->bindValue(':administrador', $params[':administrador'], PDO::PARAM_STR);
        }

        $stmt->execute();

        $usuariosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($usuariosData as $data) {
            $usuarios[] = new Usuario(
                $data['id_usuario'],
                $data['nome'],
                $data['email'],
                null,
                $data['administrador']
            );
        }

        return $usuarios;
    }
}
