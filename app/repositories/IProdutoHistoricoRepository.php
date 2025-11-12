<?php

interface IProdutoHistoricoRepository
{
    public function getHistoricoByProdutoId($id_produto, int $limit, int $offset): array;
    public function countHistoricoByProdutoId(int $id_produto): int; 
}