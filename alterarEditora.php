<?php
//1. Conectar no BD (IP, usuario, senha, nome do bd)
require_once("conexao.php");

require_once("admAutenticacao.php");

if (isset($_POST['salvar'])) {
    //2. Receber os dados para inserir no BD
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $status = $_POST['status'];

    //3. Preparar a SQL
    $sql = "update editora
    set nome= '$nome',
    status = '$status'
    where id = $id";

    //4. Executar a SQL
    mysqli_query($conexao, $sql);
    $mensagem = "Alterado com sucesso";
}

//Busca usuário selecionado pelo "usuarioListar.php"
$sql = "select * from editora where id = " . $_GET['id'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado)
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

    <title>Alterar Editora</title>
</head>

<body>
    <nav>
        <a href="main.php" style="text-decoration:none">
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
                <li><a href="#">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Logout</span>
                    </a></li>

                <li class="mode">
                    <a href="sair.php">
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
                <form method="post" class="container">
                    <?php
                    $status = isset($_POST['status']) ? $_POST['status'] : "";
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : "";
                    ?>

                </form>

                <form method="post" class="geekcb-form-contact">
                    <?php require_once("mensagem.php") ?>
                    <input type="hidden" name="id" value="<?= $linha['id'] ?>">
                    <h1 class="titulo">Alterar Editora</h1>

                    <select class="geekcb-field" name="status" id="selectbox" data-selected="">
                        <option class="fonte-status" value="" disabled="disabled" placeholder="Status">Status
                        </option>
                        <option value="Ativo" <?= ($linha['status'] == 'Ativo') ? 'selected="selected"' : '' ?>>Ativo
                        </option>
                        <option value="Inativo" <?= ($linha['status'] == 'Inativo') ? 'selected="selected"' : '' ?>>Inativo
                        </option>
                    </select>

                    <input class="geekcb-field" value="<?= $linha['nome'] ?>" placeholder="Nome" required type="texto"
                        name="nome">

                        <table>
                        <tr>
                            <td style="padding-right: 70px;width: 80%;"><a href="listarEditora.php"
                                    class="botaolistar"> <i class="fa-regular fa-file-lines"></i></i></a></td>
                            <td> <button class="geekcb-btn" type="submit" name="salvar">Salvar</button></td>

                        </tr>
                    </table>
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
</body>

</html>