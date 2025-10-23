<?php

class Produto {
    private $id_produto;
    private $nome;
    private $preco;
    private $imagem;
    private $id_categoria;
    private $nome_categoria;
    private $estoque; 
    private $ativo; 

    public function __construct(
        $id_produto = null, 
        $nome = null, 
        $preco = null, 
        $imagem = null, 
        $id_categoria = null,
        $nome_categoria = null,
        $estoque = null,
        $ativo = null
    ) {
        $this->id_produto = $id_produto;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->imagem = $imagem;
        $this->id_categoria = $id_categoria;
        $this->estoque = $estoque; 
        $this->nome_categoria = $nome_categoria;
        $this->ativo = $ativo;
    }

    public function getIdProduto() { return $this->id_produto; }
    public function setIdProduto($id_produto) { $this->id_produto = $id_produto; }

    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }

    public function getPreco() { return $this->preco; }
    public function setPreco($preco) { $this->preco = $preco; }

    public function getImagem() { return $this->imagem; }
    public function setImagem($imagem) { $this->imagem = $imagem; }

    public function getIdCategoria() { return $this->id_categoria; }
    public function setIdCategoria($id_categoria) { $this->id_categoria = $id_categoria; }

    public function getNomeCategoria() { return $this->nome_categoria; }
    public function setNomeCategoria($nome_categoria) { $this->nome_categoria = $nome_categoria; }
    
    public function getEstoque() { return $this->estoque; }
    public function setEstoque($estoque) { $this->estoque = $estoque; }

    public function getAtivo() { return $this->ativo; }
    public function setAtivo($ativo) { $this->ativo = $ativo; }
}