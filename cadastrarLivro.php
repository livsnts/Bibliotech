<?php
//1. conectar no banco de dados (ip, usuario, senha, nome do banco)
require_once("conexao.php");

require_once("admAutenticacao.php");

if (isset($_POST['cadastrar'])) {
    //2. Receber os dados para inserir no BD
    $statusLivro = $_POST['statusLivro'];
    $titulo = $_POST['titulo'];
    $pag = $_POST['pag'];
    $isbn = $_POST['isbn'];
    $edicao = $_POST['edicao'];
    $idEditora = $_POST['idEditora'];
    $idGenero = $_POST['idGenero'];

    $diretorio = "uploads/";
    $arquivoDestino = $diretorio . $_FILES['arquivo']['name'];

    $nomeArquivo = "";
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $arquivoDestino)) {
        $nomeArquivo = $_FILES['arquivo']['name'];
    } else {
        echo "ERRO: Arquivo não enviado";
    }

    //3. preparar sql para inserir usando prepared statement
    $sql = "INSERT INTO livro (statusLivro, titulo, pag, isbn, edicao, idEditora, idGenero, arquivo) VALUES ('$statusLivro','$titulo','$pag','$isbn', '$edicao','$idEditora', '$idGenero','$nomeArquivo')";

    mysqli_query($conexao, $sql);

    $idLivro = mysqli_insert_id($conexao);
    header("Location: livroAutor.php?idLivro=$idLivro&titulo=$titulo");

    $mensagem = "Cadastrado com sucesso!";

}

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

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="shortcut icon" href="logo.ico">

    <title>Cadastrar Livro</title>
</head>

<body>
    <nav>
        <a href="main.php" style="text-decoration: none">
            <div class="logo-name">
                <div class="logo-image">
                    <img src="logo.ico" alt="">
                </div>
                <span class="logo_name">Bibliotech</span>
            </div>
        </a>
        <div class="menu-items">
            <ul class="nav-links">
                <?php require_once('sidebar.php') ?>
            </ul>

            <ul class="logout-mode">
                <li><a href="sair.php">
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
                <i class="fa-solid fa-bars sidebar-toggle botaoNav"></i>
            </div>
        </div>
        <div class="corpo">
            <div class="geekcb-wrapper">
                <form method="post" class="geekcb-form-contact" enctype="multipart/form-data">
                    <a href="listarLivros.php" class="botaolistar"> <i class="fa-regular fa-file-lines"></i></i></a>
                    <h1 class="titulo">Cadastrar Livro</h1>
                    <div class="form-row">
                        <div class="form-column; esquerda">
                            <select class="geekcb-field" name="statusLivro" id="selectbox" data-selected="">
                                <option class="fonte-status" value="" disabled="disabled" placeholder="Status">Status
                                </option>
                                <option value="Disponível" selected="selected">Disponível</option>
                                <option value="Emprestado">Emprestado</option>
                            </select>
                        </div>
                        <div class="form-column">
                            <input class="geekcb-field" id="titulo" placeholder="Título do livro" required type="texto"
                                name="titulo">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <input class="geekcb-field" placeholder="Quantidade de páginas" required type="texto"
                                name="pag">
                        </div>

                        <div class="form-column">
                            <input class="geekcb-field" id="isbn" name="isbn" placeholder="ISBN" required type="texto">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <input class="geekcb-field" id="edicao" placeholder="Edição" required type="texto"
                                name="edicao">
                        </div>
                        <div class="form-column">
                            <select class="geekcb-field" name="idGenero" id="selectbox" data-selected="">
                                <option class="fonte-status" value="idGenero" selected="selected" disabled="disabled"
                                    placeholder="Gênero">Gênero</option>
                                <?php
                                $sql = "select * from genero order by nome";
                                $resultado = mysqli_query($conexao, $sql);

                                while ($linha = mysqli_fetch_array($resultado)):
                                    $id = $linha['id'];
                                    $nome = $linha['nome'];

                                    echo "<option value='{$id}'>{$nome}</option>";
                                endwhile;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <select class="geekcb-field" name="idEditora" id="selectbox" data-selected="">
                                <option class="fonte-status" value="idEditora" selected="selected" disabled="disabled"
                                    placeholder="Editora">Editora</option>
                                <?php
                                $sql = "select * from editora order by nome";
                                $resultado = mysqli_query($conexao, $sql);

                                while ($linha = mysqli_fetch_array($resultado)):
                                    $id = $linha['id'];
                                    $nome = $linha['nome'];

                                    echo "<option value='{$id}'>{$nome}</option>";
                                endwhile;
                                ?>
                            </select>
                        </div>
                        <div class="form-column">
                            <input type="file" class="geekcb-field" name="arquivo" id="arquivo">

                        </div>


                    </div>

                    <button class="geekcb-btn" type="submit" name="cadastrar">Cadastrar</button>
                </form>


            </div>
        </div>
        </div>
    </section>
    <script>
        let arrow = document.querySelectorAll(".arrow");
        for (var i = 0; i < arrow.length; i++) {
            arrow[i].addEventListener("click", (e) => {
                let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
                arrowParent.classList.toggle("showMenu");
            });
        }
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        console.log(sidebarBtn);
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#telefone').inputmask('(99) 99999-9999');
        });
        $(document).ready(function () {
            $('#dn').inputmask('99/99/9999');
        });
        $(document).ready(function () {
            $('#cpf').inputmask('999.999.999-99');
        });
    </script>

    <script src="js/script.js"></script>
</body>

</html>