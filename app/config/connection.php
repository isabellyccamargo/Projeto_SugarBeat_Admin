<?php

class Connection
{
    public static function connect()
    {
        $dsn = 'mysql:host=localhost;dbname=sugarbeat';
        $user = 'root';
        $password = 'ADMIN';
        try {
            $pdo = new PDO($dsn, $user, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erro de Conexão: " . $e->getMessage());
        }
    }
}
