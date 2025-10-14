<?php

interface IItemPedidoRepository
{
    public function getById($id);
    public function getByPedidoId($pedidoId);
    public function save($itemPedido);
    public function update($itemPedido);
    public function delete($id);
}
