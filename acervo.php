<?php
require_once("conexao.php");

$V_WHERE = "";
if (isset($_POST['pesquisar'])) { // botao pesquisar
    $V_WHERE = " AND livro.titulo LIKE '%{$_POST['pesquisa']}%' ";

}

$sql = "SELECT livro.id, editora.nome as nomeEditora, genero.nome as nomeGenero, livro.statusLivro, livro.titulo, livro.pag, livro.isbn, livro.edicao, livro.arquivo as arquivo
        FROM livro
        LEFT JOIN editora ON livro.idEditora = editora.id
        LEFT JOIN genero ON livro.idGenero = genero.id
        WHERE 1 {$V_WHERE}";


//3. Executa a SQL
$resultado = mysqli_query($conexao, $sql);

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

    <title>Acervo Bibliotech</title>
</head>

<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <img src="images/logo.png" alt="">
            </div>

            <span class="logo_name">Bibliotech</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <?php require_once('sidebar.php') ?>
            </ul>

            <ul class="logout-mode">
                <li><a href="#">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </a></li>

                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                        <span class="link-name">Dark Mode</span>
                    </a>

                    <div class="mode-toggle">
                        <span class="switch"></span>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">

        <div class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <i class="uil uil-bars sidebar-toggle"></i>


            </div>
        </div>

        <h1 class="titulo">Acervo</h1><br><br>
        <center>
            <form method="post">
                <label name="pesquisa" for="exampleFormControlInput1" class="titulo">Pesquisar</label>
                <div class="input-button-container">
                    <input name="pesquisa" type="text" class="formcampo">
                    <button name="pesquisar" stype="button" class="botaopesquisar">Pesquisar</button>
                    <a href="acervo.php"><button name="voltar" stype="button" class="botaopesquisar">Voltar</button></a>
                </div>
                <br><br>
            </form><br><br>
        </center>



        <?php while ($linha = mysqli_fetch_array($resultado)) { ?>


            <div class="wrapperAcervo">

                <div class="containerAcervo">
                    <div style="background-image: url('uploads/<?= $linha['arquivo'] ?>')" class="topAcervo"></div>
                    <div class="bottomAcervo">
                        <div class="leftAcervo">
                            <div class="detailsAcervo">
                                <h5 style="width: 50%; margin-top: 5%; text-align:center">
                                    <?= $linha['titulo'] ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                 </div>
                 <div class="insideAcervo">
                    <div class="icon"><i class="fa-solid fa-plus"></i></div>
                    <div class="contentsAcervo">
                        <table style="color: white">
                            <tr>
                                <th>Código do livro: </th>
                                <td>
                                    <?= $linha['id'] ?>
                                </td>

                            </tr>

                            <tr>
                                <th>Status do livro: </th>
                                <td>
                                    <?= $linha['statusLivro'] ?>
                                </td>

                            </tr>
                            <tr>
                                <th>Autor(es): </th>
                                <td>

                                    <?php
                                    $idLivro = $linha['id'];
                                    $sqlAutores = "SELECT autor.nome FROM livroautor
                                            JOIN autor ON livroautor.idAutor = autor.id
                                            WHERE livroautor.idLivro = $idLivro";

                                    $resultadoAutor = mysqli_query($conexao, $sqlAutores);

                                    $autores = array();
                                    while ($linhaAutor = mysqli_fetch_array($resultadoAutor)) {
                                        $autores[] = $linhaAutor['nome'];

                                    }
                                    echo implode(', ', $autores);

                                    ?>

                                </td>
                            </tr>
                            <tr>
                                <th>ISBN: </th>
                                <td>
                                    <?= $linha['isbn'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>N° de páginas: </th>
                                <td>
                                    <?= $linha['pag'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Gênero: </th>
                                <td>
                                    <?= $linha['nomeGenero'] ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Editora: </th>
                                <td>
                                    <?= $linha['nomeEditora'] ?>
                                </td>

                            </tr>

                        </table>
                    </div>
                </div>
            </div>


        <?php } ?>




        <script>
            $('.buyAcervo').click(function () {
                $('.bottomAcervo').addClass("clicked");
            });

            $('.removeAcervo').click(function () {
                $('.bottomAcervo').removeClass("clicked");
            });
        </script>

        <?php require_once("rodape.php");