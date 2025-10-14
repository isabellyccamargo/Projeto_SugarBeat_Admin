<?php
interface IClienteRepository
{
    public function getById($id);
     public function getClienteByEmail($email);
      public function getAll(): array;
}
