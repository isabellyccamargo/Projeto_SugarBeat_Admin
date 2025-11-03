<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feira das frutas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>
    <h1>Frutas em destaque</h1>
    <?php
    include "frutas.php";
    
   
//    print_r($_GET);

    $indice = $_GET['indice'];
    // echo $indice;
    echo "<h2>Nome do produto: {$frutas[$indice]['nome']}</h2>";
    echo"<h2>Cor do produto: {$frutas[$indice]['cor']}</h2>";
     echo"<h2>Valor do produto: {$frutas[$indice]['valor']}</h2>";
    ?>
</body>
</html>