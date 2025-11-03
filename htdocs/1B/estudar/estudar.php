<?php

$nota1 = 7;
$nota2 = 8;
$nota3 = 10;

$media = number_format(($nota1 + $nota2 + $nota3) / 3);
echo "Média: $media <br>";

if ($media >= 7) {
    echo "Aprovado";
} else {
    echo "Reprovado";
};
