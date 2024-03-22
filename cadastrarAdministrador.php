<?php
//1. conectar no banco de dados (ip, usuario, senha, nome do banco)
require_once("conexao.php");

require_once("admAutenticacao.php");

$emailRepetido = "";
$senhaInvalido = "";
$senhaPequena = "";

if(isset($_POST['cadastrar'])) {
    //2. Receber os dados para inserir no BD
    $status = $_POST['status'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $confirmarSenha = $_POST['confirmarSenha'];

    $sqlEmail = "SELECT email FROM leitor WHERE email = '".$login."'";
    $verificaEmail = mysqli_query($conexao, $sqlEmail);
    $numeroLinhas = mysqli_num_rows($verificaEmail);

    $sqlEmail2 = "SELECT * FROM administrador WHERE login = '".$login."'";
    $verificaEmail2 = mysqli_query($conexao, $sqlEmail2);
    $numeroLinhas2 = mysqli_num_rows($verificaEmail2);

    if($numeroLinhas < 1 && $numeroLinhas2 < 1) {
        if(strlen($senha) >= 8) {
            if($senha == $confirmarSenha) {
                //3. preparar sql para inserir
                $sql = "insert into administrador (status, login, senha)
                values ('$status', '$login', '$senha')";

                //4. executar sql no bd
                mysqli_query($conexao, $sql);

                //5.mostrar uma mensagem ao usuário
                $mensagem = "Cadastro realizado com sucesso!";
            } else {
                $mensagemAlert = "Erro ao cadastrar";
                $senhaInvalido = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Senhas não correspondentes</span>';
            }
        } else {
            $mensagemAlert = "Erro ao cadastrar";
            $senhaPequena = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Senha necessita de 8 caracteres</span>';
        }
    } else {
        $mensagemAlert = "Erro ao cadastrar";
        $emailRepetido = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Email já cadastrado no sistema</span>';
    }
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

    <title>Cadastrar Administrador</title>
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
                <form method="post" class="container">
                    <?php
                    $status = isset($_POST['status']) ? $_POST['status'] : "";
                    $nome = isset($_POST['login']) ? $_POST['login'] : "";
                    $senha = isset($_POST['senha']) ? $_POST['senha'] : "";
                    ?>

                </form>

                <form method="post" class="geekcb-form-contact">
                    <?php require_once("mensagem.php"); ?>
                    <h1 class="titulo">Cadastrar Administrador</h1>

                    <select class="geekcb-field" id="selectbox" data-selected="" name="status">
                        <option class="fonte-status" value="" disabled="disabled" placeholder="Status">Status
                        </option>
                        <option value="Ativo" selected="selected">Ativo</option>
                        <option value="Inativo">Inativo</option>
                    </select>

                    <div class="form-row">
                        <input class="geekcb-field" placeholder="Login" value="<?= $login ?>" required type="text" name="login">
                        <?php echo $emailRepetido; ?>
                    </div>

                    <div class="form-row">
                        <input class="geekcb-field" placeholder="Senha" value="<?= $senha ?>" required type="password" name="senha">
                        <?php echo $senhaPequena; ?>
                    </div>

                    <div class="form-row">
                        <input class="geekcb-field" placeholder="Confirmar Senha" required type="password"
                            name="confirmarSenha">
                        <?php echo $senhaInvalido; ?>
                    </div>

                    <table>
                        <tr>
                            <td style="padding-right: 70px;width: 80%;"><a href="listarAdministrador.php"
                                    class="botaolistar"> <i class="fa-regular fa-file-lines"></i></i></a></td>
                            <td> <button class="geekcb-btn" type="submit" name="cadastrar">Cadastrar</button></td>

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