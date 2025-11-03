<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalhando com arrays</title>
</head>
<body>
    <h1>ARRAYS</h1>
    <?php
        echo "<p> Vamos aprender arrays:</p>";

        $nome = "Isabelly";
        $carros = array("Marea 24V" , "Fusca 1300" , "Fiat 147" , "DelRey Ghia");

        // print_r(value: $carros);

        echo $carros[1];

        foreach ($carros as $dados) {
            echo "<p>{$dados}</p>";
        }

        array_push ($carros, "Opala");
        array_push ( $carros, "Monza");

         echo "<p> ----------------------- </p>";
        foreach ($carros as $dados) {
            echo "<p> $dados </p>" ;
        }
         
          echo "<p> ----------------------- </p>";

        if (in_array("Monza", $carros)) {
            echo"<p> Monza Encontrado </p>";
        } else {
            echo "<p> Monza não encontrado </p>";
        }

        echo "<p> ----------------------- </p>";
        unset($carros[0]);
        print_r($carros);


        echo "<p> ----------------------- </p>";
        $carros[0] = "Corsel";
         print_r($carros);
    ?>
</body>
</html>