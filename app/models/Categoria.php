<?php

class Categoria
{
    private $id_categoria;
    private $nome_categoria;

    public function __construct($id_categoria = null, $nome_categoria = null)
    {
        $this->id_categoria = $id_categoria;
        $this->nome_categoria = $nome_categoria;
    }


    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    public function getNomeCategoria()
    {
        return $this->nome_categoria;
    }

    public function setNomeCategoria($nome_categoria)
    {
        $this->nome_categoria = $nome_categoria;
    }
}
