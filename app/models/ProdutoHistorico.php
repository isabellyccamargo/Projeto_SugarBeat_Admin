<?php

class ProdutoHistorico
{
    private $id_historico;
    private $data;
    private $id_usuario;
    private $operacao;
    private $valor_antigo;
    private $valor_atual;
    private $id_produto;

    public function __construct(
        $id_historico = null,
        $data = null,
        $id_usuario = null,
        $operacao = null,
        $valor_antigo = null,
        $valor_atual = null,
        $id_produto = null
    ) {
        $this->id_historico = $id_historico;
        $this->data = $data;
        $this->id_usuario = $id_usuario;
        $this->operacao = $operacao;
        $this->valor_antigo = $valor_antigo;
        $this->valor_atual = $valor_atual;
        $this->id_produto = $id_produto;
    }


    public function getIdHistorico()
    {
        return $this->id_historico;
    }

    public function setIdHistorico($id_historico)
    {
        $this->id_historico = $id_historico;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getOperacao()
    {
        return $this->operacao;
    }

    public function setOperacao($operacao)
    {
        $this->operacao = $operacao;
    }

    public function getValorAntigo()
    {
        return $this->valor_antigo;
    }

    public function setValorAntigo($valor_antigo)
    {
        $this->valor_antigo = $valor_antigo;
    }

    public function getValorAtual()
    {
        return $this->valor_atual;
    }

    public function setValorAtual($valor_atual)
    {
        $this->valor_atual = $valor_atual;
    }

    public function getIdProduto()
    {
        return $this->id_produto;
    }

    public function setIdProduto($id_produto)
    {
        $this->id_produto = $id_produto;
    }
}
