<?php

class ItemPedido implements JsonSerializable
{
    private $id; 
    private $id_pedido;
    private $id_produto;
    private $quantidade;
    private $preco_unitario;
    private $sub_total;

    public function __construct($id, $id_pedido,  $id_produto,  $quantidade,  $preco_unitario,  $sub_total)
    {
        $this->id = $id;
        $this->id_pedido = $id_pedido;
        $this->id_produto = $id_produto;
        $this->quantidade = $quantidade;
        $this->preco_unitario = $preco_unitario;
        $this->sub_total = $sub_total;
    }

    public function getId()
    {
        return $this->id;
    }
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getIdPedido()
    {
        return $this->id_pedido;
    }
    public function setIdPedido($id_pedido): void
    {
        $this->id_pedido = $id_pedido;
    }

    public function getIdProduto()
    {
        return $this->id_produto;
    }
    public function setIdProduto($id_produto): void
    {
        $this->id_produto = $id_produto;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }
    public function setQuantidade($quantidade): void
    {
        $this->quantidade = $quantidade;
    }
    public function getPrecoUnitario()
    {
        return $this->preco_unitario;
    }
    public function setPrecoUnitario($preco_unitario): void
    {
        $this->preco_unitario = $preco_unitario;
    }

    public function getSubTotal()
    {
        return $this->sub_total;
    }
    public function setSubTotal($sub_total): void
    {
        $this->sub_total = $sub_total;
    }

    public function jsonSerialize(): mixed
    {

        return [
            'id_pedido' => $this->id_pedido,
            'id_produto' => $this->id_produto,
            'quantidade' => $this->quantidade,
            'preco_unitario' => $this->preco_unitario,
            'sub_total' => $this->sub_total
        ];
    }
}
