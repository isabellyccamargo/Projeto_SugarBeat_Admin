<?php

class Usuario
{
    private $id_usuario;
    private $nome;
    private $email;
    private $senha;
    private $administrador; // 'S' ou 'N'

    public function __construct(
        $id_usuario = null,
        $nome = null,
        $email = null,
        $senha = null,
        $administrador = 'N'
    ) {
        $this->id_usuario = $id_usuario;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->administrador = $administrador;
    }


    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getAdministrador()
    {
        return $this->administrador;
    }

    public function setAdministrador($administrador)
    {
        $this->administrador = $administrador;
    }

    // Checkers
    public function isAdministrador(): bool
    {
        return strtoupper($this->administrador) === 'S';
    }
}
