<?php
//1. Conectar no BD (IP, usuario, senha, nome do bd)
require_once("conexao.php");

require_once("admAutenticacao.php");

if (isset($_POST['salvar'])) {
    //2. Receber os dados para inserir no BD

    $idLeitor = $_POST['leitor'];
    $statusEmprestimo = $_POST['statusEmprestimo'];
    $dataEmprestimo = $_POST['dataEmprestimo'];
    $dataDevolucao = $_POST['dataDevolucao'];
    $dataPrevistaDevolucao = $_GET['dataPrevistaDevolucao'];
    $hoje = date("Y-m-d");

    $datadehoje = date_create();
    $resultado = date_diff($hoje, $dataPrevistaDevolucao);
    echo date_interval_format($resultado, '%a');



    //3. Preparar a SQL
    $sql = "UPDATE emprestimo
    set dataDevolucao = '$dataDevolucao',
    where id = $id";

    //4. Executar a SQL
    mysqli_query($conexao, $sql);
}

if (isset($_POST['livro[]']) && is_array($_POST['livro[]'])) {
    foreach ($_POST['livro[]'] as $idLivro) {
        $sql2 = "UPDATE itensdeemprestimo SET idLivro = '$idLivro' WHERE id = " . $_GET['id'];

        mysqli_query($conexao, $sql2);
    }
}

if (isset($_POST['salvar'])) {
    header("Location: listarEmprestimo.php");
    exit;
}

//Busca usuário selecionado pelo "usuarioListar.php"
$sql = "SELECT * FROM emprestimo WHERE id = " . $_GET['id'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado);
?>
<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="shortcut icon" href="logo.ico">

    <title>Alterar Emprestimo</title>
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

        <div class="corpo">
            <div class="top">
                <i class="fa-solid fa-bars sidebar-toggle botaoNav"></i>
            </div>
            <div class="geekcb-wrapper">
                <form method="post" class="container">
                    <?php
                    $dataPrevistaDevolucao = isset($_POST['dataPrevistaDevolucao']) ? $_POST['dataPrevistaDevolucao'] : "";
                    ?>

                </form>

                <form method="post" class="geekcb-form-contact">
                    <input type="hidden" name="id" value="<?= $linha['id'] ?>">
                    <input type="hidden" name="dataPrevistaDevolucao" id="dataPrevistaDevolucaoInput"
                        value="<?= $dataPrevistaDevolucao ?>">

                    <h1 class="titulo">Finalizar empréstimo</h1>

                    <select class="geekcb-field" name="statusEmprestimo" id="selectbox" data-selected="">
                        <option class="fonte-status" value="<?= $linha['statusEmprestimo'] ?>" disabled="disabled"
                            placeholder="Status">Status</option>
                        <option value="Em andamento">Em andamento</option>
                        <option selected="selected" value="Finalizado">Finalizado</option>
                    </select>

                    <br><br>
                    <label for="livro" class="titulo" style="font-size:1.2rem; text-align: left">Selecione o(s) livros
                        para empréstimo:
                    </label>
                    <select class="selectleitor" name="livro[]" id="livro" multiple>
                        <option class="fonte-status" disabled="disabled" placeholder="Selecione o livro"></option>
                        <?php
                        $sql = "select * from livro order by titulo";
                        $resultado = mysqli_query($conexao, $sql);

                        while ($linha = mysqli_fetch_array($resultado)):
                            $idLivro = $linha['id'];
                            $titulo = $linha['titulo'];
                            $isbn = $linha['isbn'];
                            $edicao = $linha['edicao'];


                            echo "<option value='{$idLivro}'>{$titulo} - Ed. {$edicao} - ISBN: {$isbn} </option>";
                        endwhile;

                        ?>

                    </select>
                    <br><br>

                    <input type="hidden" name="dataDevolucao" id="dataDevolucaoInput" value="<?= $dataDevolucao ?>">


                    <button class="geekcb-btn" type="submit" name="salvar">Salvar</button>
                    <button class="geekcb-btn" type="submit" name="renovar">Renovar livro</button>
                </form>
            </div>
        </div>
        <?php
        require_once("procurarEmprestimo.php");
        ?>
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

    <script>
        $(document).ready(function () {
            $('#leitor').select2();
        });
        $(document).ready(function () {
            $('#livro').select2();
        });
    </script>
</body>

</html>