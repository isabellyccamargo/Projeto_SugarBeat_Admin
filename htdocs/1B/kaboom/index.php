<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaboom</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-warning">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
        <img src="imagens/logo.png">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link" href="#">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="quem-somos">Quem Somos</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="produtos">Produtos</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<main class="container">
    <?php 

       echo $pagina = $_GET["param"] ?? "home";

    ?>
</main>

<footer class="bg-warning p-4">

    <p class="text-center">
        Kaboom todos os direitos reservados!
        Desenvolvido por Isabelly.
</footer>
</body>
</html>