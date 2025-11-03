<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site da Isa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>
    <div class="container">
    <header class="text-center">
    <h1>Site da Isa</h1>
    <nav>
        <a href="home" class="btn btn-danger">
            Página Inicial
        </a>
       <a href="quem-somos" class="btn btn-danger">
            Quem Somos
       </a>
       <a href="servicos" class="btn btn-danger">
            Nossos Serviços
       </a>
       <a href="contato" class="btn btn-danger">
            Contato
        </a>
       
    </header>

    <main>
        <?php
            //MOSTRAR O GET
            // PRINT-R($_GET);

             echo$param = $_GET["param"] ?? "home";

             $pagina = "paginas/{$param}.php";

             //VERIFICAR SE ESTE ARQUIVO EXISTE
             if (file_exists($pagina))
             include $pagina;
            else
            include "paginas/erro.php"; 
        ?>
    </main>

    <footer class="bg-danger">
        <p class="text-center">
            Desenvolvido por Isa
        </p>
    </footer>
     
    </div>
</body>
</html>