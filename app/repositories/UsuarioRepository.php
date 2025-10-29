<?php

require_once 'IUsuarioRepository.php';

class UsuarioRepository implements IUsuarioRepository
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById($id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id_usuario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Criação Direta (como no ClienteRepository)
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

    public function getByEmail($email): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT id_usuario, nome, email, senha, administrador 
            FROM usuario 
            WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $usuario = new Usuario(); // Instancia o objeto vazio

        // Atribui os valores um por um via Setter
        $usuario->setIdUsuario($data['id_usuario']);
        $usuario->setNome($data['nome']); // Garante que o nome é atribuído
        $usuario->setEmail($data['email']);
        $usuario->setSenha($data['senha']);
        $usuario->setAdministrador($data['administrador']);

        return $usuario;
    }

    public function getAll(?string $adminStatus = null): array
    {
        $sql = "SELECT * FROM usuario";
        $params = [];

        if (in_array($adminStatus, ['S', 'N'])) {
            $sql .= " WHERE administrador = :admin";
            $params[':admin'] = $adminStatus;
        }

        $sql .= " ORDER BY nome ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($dataList as $data) {
            $usuarios[] = new Usuario(
                $data['id_usuario'],
                $data['nome'],
                $data['email'],
                $data['senha'],
                $data['administrador']
            );
        }

        return $usuarios;
    }
    public function save(Usuario $usuario): Usuario
    {
        $sql = "INSERT INTO usuario (nome, email, senha, administrador) 
                VALUES (:nome, :email, :senha, :administrador)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':administrador', $usuario->getAdministrador());
        $stmt->execute();

        $usuario->setIdUsuario($this->db->lastInsertId());
        return $usuario;
    }

    public function update(Usuario $usuario): Usuario
    {
        $set = "nome = :nome, email = :email, administrador = :administrador";
        if (!empty($usuario->getSenha())) {
            $set .= ", senha = :senha";
        }

        $sql = "UPDATE usuario SET {$set} WHERE id_usuario = :id";
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

    public function countAll(?string $adminFilter): int
    {
        $sql = "SELECT COUNT(*) FROM usuario";
        $params = [];

        if (in_array($adminFilter, ['S', 'N'])) {
            $sql .= " WHERE administrador = :administrador";
            $params[':administrador'] = $adminFilter;
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getPaginated(int $limit, int $offset, ?string $adminFilter): array
    {
        $sql = "SELECT id_usuario, nome, email, administrador FROM usuario";
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

        if (in_array($adminFilter, ['S', 'N'])) {
            $stmt->bindValue(':administrador', $adminFilter, PDO::PARAM_STR); 
        }

        $stmt->execute();

        $usuariosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = [];

        foreach ($usuariosData as $data) {
            $usuarios[] = new Usuario(
                $data['id_usuario'],
                $data['nome'],
                $data['email'],
                null, // Não está selecionando a senha no SELECT (correto), passe null
                $data['administrador']
            );
        }

        return $usuarios;
    }
}
