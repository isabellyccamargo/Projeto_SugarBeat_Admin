<?php

class Cliente implements JsonSerializable
{
    private $id_cliente;
    private $nome;
    private $cpf;
    private $email;
    private $senha;
    private $cidade;
    private $bairro;
    private $rua;
    private $numero_da_casa;
    private $data_cadastro;

    public function __construct($id_cliente = null, $nome = null, $cpf = null, $email = null, $senha = null, $cidade = null, $bairro = null, $rua = null, $numero_da_casa = null, $data_cadastro = null)
    {
        $this->id_cliente = $id_cliente;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->email = $email;
        $this->senha = $senha;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->rua = $rua;
        $this->numero_da_casa = $numero_da_casa;
        $this->data_cadastro = $data_cadastro;
    }

    public function getIdCliente()
    {
        return $this->id_cliente;
    }
    public function setIdCliente($id_cliente)
    {
        $this->id_cliente = $id_cliente;
    }

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCpf()
    {
        return $this->cpf;
    }
    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
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

    public function getCidade()
    {
        return $this->cidade;
    }
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;
    }

    public function getBairro()
    {
        return $this->bairro;
    }
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
    }

    public function getRua()
    {
        return $this->rua;
    }
    public function setRua($rua)
    {
        $this->rua = $rua;
    }

    public function getNumeroDaCasa()
    {
        return $this->numero_da_casa;
    }
    public function setNumeroDaCasa($numero_da_casa)
    {
        $this->numero_da_casa = $numero_da_casa;
    }

    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }
    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id_cliente' => $this->id_cliente,
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'senha' => $this->senha,
            'cidade' => $this->cidade,
            'bairro' => $this->bairro,
            'rua' => $this->rua,
            'numero_da_casa' => $this->numero_da_casa,
            'data_cadastro' => $this->data_cadastro
        ];
    }
}
