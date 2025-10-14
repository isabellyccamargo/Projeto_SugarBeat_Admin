<?php

interface IProdutoRepository {
    public function getById($id);
    public function getAll();
    public function save($produto);
    public function update($produto);
    public function delete($id);
}