<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feira da Fruta</title>
    
</head>
<body>
    <h1>Feira da fruta</h1>
    <?php
    //require o uso de outro arquivo
    require "frutas.php";

    // print_r($frutas[1]);

    foreach ($frutas as $dados) {
        echo "<p>{$dados['nome']} - {$dados['cor']}</p>";
    }
    ?>
</body>
</html>