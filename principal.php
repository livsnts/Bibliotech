<?php
require_once('leitorAutenticacao.php');
$voltar = "";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <!--muda a fonte-->
    <script src="https://kit.fontawesome.com/e507e7a758.js" crossorigin="anonymous"></script>

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastrar.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/acervo.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="shortcut icon" href="logo.ico">

    <title>Bibliotech</title>
</head>

<body style="background-color: #ffd8be">
<nav class="navbar navbar-expand-lg" style="background-color: #ffd8be">
  <div class="container-fluid">
  <img src="logobiblio.png" alt="logo" width="50px">
    <a class="navbar-brand titulo" style="font-size: 2rem; padding-right: 10%" href="principal.php">Bibliotech</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="meusEmprestimos2.php">Meus empréstimos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="renovarLeitor2.php">Renovar livros</a>
        </li>
       
       
      </ul>
      <form class="d-flex">
<ul>
      <li style="justify-content: end">
        <a class="nav-link" href="sair.php" style="color: #9381ff"><i class="fa-solid fa-right-from-bracket"></i>Sair</a>
        </li></ul>
      </form>
      
    </div>
  </div>
</nav>


    </div>

    <div class="container">
        <br><br>
        <h2 class="mt-3" style="font-family: Fjalla One">
            <?php

            $nome = $_SESSION['nome'];
            ?>
            Seja bem vindo(a),
            <?= $nome ?>
        </h2><br>

    </div>

    <center>
        <form method="post">
            <label name="pesquisa" for="exampleFormControlInput1" class="titulo text">Pesquisar livros no acervo da
                biblioteca</label>
            <div class="input-button-container">
                <input name="pesquisa" type="text" class="formcampo">
                <button name="pesquisar" stype="button" class="botaopesquisarAcervo"><i
                        class="fa-solid fa-magnifying-glass"></i>
                </button>
                <?= $voltar; ?>
            </div>
            <br><br>
        </form>
    </center>

    <div class="acervocontainer">

        <?php require_once('acervo1.php') ?>
    </div>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>

</html>