<?php

require_once("conexao.php");

require_once("admAutenticacao.php");

$cpfInvalido = isset($_SESSION['cpfInvalido']) ? $_SESSION['cpfInvalido'] : "";
unset($_SESSION['cpfInvalido']);
if (isset($_POST['cadastrar'])) {
    //2. Receber os dados para inserir no BD
    $idUsuario = $_POST['id_usuario'];
    $nomeResp = $_POST['nomeResp'];
    $telResp = $_POST['telResp'];
    $cpfResp = $_POST['cpfResp'];

    $_SESSION['form_values'] = [
        'nomeResp' => $nomeResp,
        'cpfResp' => $cpfResp,
        'telResp' => $telResp,
        // Adicione mais campos conforme necessário
    ];
    function validarCPFResp($cpfResp)
    {
        // Remove caracteres não numéricos
        $cpfResp = preg_replace('/[^0-9]/', '', $cpfResp);

        // Verifica se o CPF possui 11 dígitos
        if (strlen($cpfResp) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpfResp)) {
            return false;
        }

        // Calcula o primeiro dígito verificador
        $soma = 0;
        for ($i = 0; $i < 9; $i++) {
            $soma += $cpfResp[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        // Calcula o segundo dígito verificador
        $soma = 0;
        for ($i = 0; $i < 10; $i++) {
            $soma += $cpfResp[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        // Verifica se os dígitos verificadores estão corretos
        if ($cpfResp[9] == $digito1 && $cpfResp[10] == $digito2) {
            return true;
        } else {
            return false;
        }
    }

    if (validarCPFResp($cpfResp)) {
        // Montar a consulta SQL de atualização
        $sql = "UPDATE leitor SET nomeResp = '$nomeResp', telResp = '$telResp', cpfResp = '$cpfResp' WHERE id = $idUsuario";
        mysqli_query($conexao, $sql);
        $cpfInvalido = "";
        $mensagem = "Cadastrado com sucesso!";
        header("Location: cadastrarLeitor.php?mensagem=$mensagem");

    } elseif (!validarCPFResp($cpfResp)) {
        $cpfInvalido = '<span style="margin-top: -26pt; margin-bottom: 10pt; color: red; font-family: Fjalla One;">Cpf Inválido</span>';
        session_start(); // Inicia a sessão
        $_SESSION['cpfInvalido'] = $cpfInvalido;
        header("Location: cadastrarResponsavel.php?idusuario=$idUsuario&nomeResp=$nomeResp&cpfResp=$cpfResp&telResp=$telResp");
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

    <title>Cadastrar Responsável</title>
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
                    $nomeResp = isset($_POST['nomeResp']) ? $_POST['nomeResp'] : "";
                    $cpfResp = isset($_POST['cpfResp']) ? $_POST['cpfResp'] : "";
                    $telResp = isset($_POST['telResp']) ? $_POST['telResp'] : "";
                    $idUsuario = isset($_POST['idUsuario']) ? $_POST['id_usuario'] : "";
                    ?>

                </form>

                <form action="cadastrarResponsavel.php" method="post" class="geekcb-form-contact">
                    <input type="hidden" name="id_usuario" value="<?php echo $_GET['idusuario']; ?>">

                    <h1 class="titulo">Cadastrar Responsável</h1>

                    <input class="geekcb-field" placeholder="Nome do responsável" required
                        value="<?= isset($_SESSION['form_values']['nomeResp']) ? $_SESSION['form_values']['nomeResp'] : ''; ?>"
                        type="texto" name="nomeResp">
                    <div class="form-column">
                        <input class="geekcb-field"
                            value="<?= isset($_SESSION['form_values']['cpfResp']) ? $_SESSION['form_values']['cpfResp'] : ''; ?>"
                            id="cpf" placeholder="CPF do responsável" required type="texto" name="cpfResp">
                        <?php echo $cpfInvalido; ?>
                    </div>
                    <input class="geekcb-field"
                        value="<?= isset($_SESSION['form_values']['telResp']) ? $_SESSION['form_values']['telResp'] : ''; ?>"
                        id="telefone" placeholder="Telefone do responsável" required type="texto" name="telResp">


                    <button class="geekcb-btn" type="submit" name="cadastrar">Cadastrar</button>
                </form>
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