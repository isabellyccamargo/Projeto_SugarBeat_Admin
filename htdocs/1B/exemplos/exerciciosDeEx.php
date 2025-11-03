<?php
 
 $frutas = array ("Abacate", "Limão", "Laranja");

    foreach ($frutas as $f) {
           echo "<p> {$f} </p>";
    }
      
    for ($i=0; $i<=3; $i+1) {
            $f = $frutas [$i];
            echo "<p>{$f}</p>";
    }

?>