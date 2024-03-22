<?php
//1. conectar no banco de dados (ip, usuario, senha, nome do banco)
require_once("conexao.php");

require_once("admAutenticacao.php");

if (isset($_POST['cadastrar'])) {
    //2. Receber os dados para inserir no BD

    $idLivro = $_GET['idLivro'];
    $titulo = $_GET['titulo'];
    $idAutor = $_POST['autor'];

    foreach ($idAutor as $idAutor) {
        $sql = "INSERT INTO livroautor (idLivro, idAutor) VALUES ('$idLivro','$idAutor')";
        mysqli_query($conexao, $sql);
        header("Location: cadastrarLivro.php");
    }

}


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
    <script src="https://kit.fontawesome.com/e507e7a758.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastrar.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Administrador Bibliotech</title>
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
                <form method="post" class="geekcb-form-contact" id="insert_data">
                    <input type="hidden" name="idLivro" value="<?php echo $_GET['idLivro'] ?>">
                    <h1 class="titulo" name="tituloLivro">
                        <?php echo $_GET['titulo']; ?>
                    </h1>
                    <label for="autor">Selecione o(s) autor(es) do livro: </label>
                    <br>
                    <select class="geekcb-field" name="autor[]" id="autor" multiple>
                        <option class="fonte-status" disabled="disabled" placeholder="Selecione os autores"></option>
                        <?php
                        $sql = "select * from autor order by nome";
                        $resultado = mysqli_query($conexao, $sql);

                        while ($linha = mysqli_fetch_array($resultado)):
                            $idAutor = $linha['id'];
                            $nome = $linha['nome'];

                            echo "<option value='{$idAutor}'>{$nome}</option>";
                        endwhile;
                        ?>

                    </select>
                    <br><br>
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
    <script>
        $(document).ready(function () {
            $('#autor').select2();
        });
    </script>
</body>

</html>