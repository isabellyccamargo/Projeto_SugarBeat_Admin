<?php

$salario = 19800;

//se o salario for maior que 10000 - BURGUES
//se o salario for maior que 5000 - TA BOM
//se o salario for menor ou igual a 5000 - TA MEIO RUIM

// if ($salario > 10000) {
//     echo "<p>Está burgues</p>";
// } else if ($salario > 5000) {
//     echo "<p>Está bom</p>";
// } else {
//     echo "<p>Está meio ruim</p>";
// }


// $numero = 10;

// for($x = 0; $x <= $numero; $x++) {
//     echo "<p>{$x}</p>";
// }

// $x = 0;
// $numero = 7;
// while ($x <= $numero) {
//     echo "<p>{$x}</p>";
//     $x++;
// }

$valor = 5000;
$parcelas = 5;
$juros = 1.89;
$jurosDividioPorCem = $juros / 100;

$Montante = $valor * (1 + $jurosDividioPorCem) ** 5;
$Montante = number_format($Montante,2,",",".");
echo "O valor da montante é {$Montante}";

