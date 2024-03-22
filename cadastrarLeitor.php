<?php

//1. conectar no banco de dados (ip, usuario, senha, nome do banco)
require_once("conexao.php");

require_once("admAutenticacao.php");

$cpfInvalido = "";
$emailInvalido = "";
$senhaInvalido = "";
$emailRepetido = "";
$senhaPequena = "";

if(isset($_GET['mensagem'])) {
    $mensagem = $_GET['mensagem'];
}
if(isset($_POST['cadastrar'])) {
    //2. Receber os dados para inserir no BD
    $status = $_POST['status'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cpf = $_POST['cpf'];
    $dn = $_POST['dn'];
    $dnFormatted = date('d/m/Y', strtotime($dn));
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirmarEmail = $_POST['confirmarEmail'];
    $confirmarSenha = $_POST['confirmarSenha'];

    $sqlCpf = "SELECT * FROM leitor WHERE cpf = '".$cpf."'";
    $verificaCpf = mysqli_query($conexao, $sqlCpf);
    $numeroLinhasCpf = mysqli_num_rows($verificaCpf);

    function validarCPF($cpf) {
        // Remove caracteres não numéricos
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        // Verifica se o CPF possui 11 dígitos
        if(strlen($cpf) != 11) {
            return false;
        }

        // Verifica se todos os dígitos são iguais
        if(preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula o primeiro dígito verificador
        $soma = 0;
        for($i = 0; $i < 9; $i++) {
            $soma += $cpf[$i] * (10 - $i);
        }
        $resto = $soma % 11;
        $digito1 = ($resto < 2) ? 0 : 11 - $resto;

        // Calcula o segundo dígito verificador
        $soma = 0;
        for($i = 0; $i < 10; $i++) {
            $soma += $cpf[$i] * (11 - $i);
        }
        $resto = $soma % 11;
        $digito2 = ($resto < 2) ? 0 : 11 - $resto;

        // Verifica se os dígitos verificadores estão corretos
        if($cpf[9] == $digito1 && $cpf[10] == $digito2) {
            return true;
        } else {
            return false;
        }
    }

    $sqlEmail = "SELECT email FROM leitor WHERE email = '".$email."'";
    $verificaEmail = mysqli_query($conexao, $sqlEmail);
    $numeroLinhas = mysqli_num_rows($verificaEmail);

    $sqlEmail2 = "SELECT * FROM administrador WHERE login = '".$email."'";
    $verificaEmail2 = mysqli_query($conexao, $sqlEmail2);
    $numeroLinhas2 = mysqli_num_rows($verificaEmail2);

    if(validarCPF($cpf)) {
        if($numeroLinhasCpf < 1) {
            if($numeroLinhas < 1 && $numeroLinhas2 < 1) {
                if($email == $confirmarEmail) {
                    if(strlen($senha) >= 8) {
                        if($senha == $confirmarSenha) {
                            //3. preparar sql para inserir
                            $sql = "insert into leitor (status, nome, telefone, endereco, cpf, dn, email, senha)
                        values ('$status', '$nome', '$telefone', '$endereco','$cpf', '$dn', '$email', '$senha')";

                            $cpfInvalido = "";

                            // Criar objetos DateTime para a data de nascimento e a data atual
                            $dataNascimentoObj = new DateTime($dn);
                            $dataAtualObj = new DateTime();

                            // Calcular a diferença entre as datas
                            $diferenca = $dataNascimentoObj->diff($dataAtualObj);

                            // Obter a idade em anos
                            $idade = $diferenca->y;

                            //4. executar sql no bd
                            mysqli_query($conexao, $sql);

                            //5.mostrar uma mensagem ao usuário
                            $mensagem = "Cadastro realizado com sucesso!";

                            if($idade < 18) {
                                $idUsuario = mysqli_insert_id($conexao);
                                header("Location: cadastrarResponsavel.php?idusuario=$idUsuario");
                                exit;
                            }
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
                    $emailInvalido = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Emails não correspondentes</span>';
                }
            } else {
                $mensagemAlert = "Erro ao cadastrar";
                $emailRepetido = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Email já cadastrado no sistema</span>';
            }
        } else {
            $mensagemAlert = "Erro ao cadastrar";
            $cpfInvalido = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Cpf já cadastrado no sistema</span>';
        }
    } else {
        $mensagemAlert = "Erro ao cadastrar";
        $cpfInvalido = '<span style="margin-top: -26pt; color: red; font-family: Fjalla One;">Cpf Inválido</span>';
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

    <title>Cadastrar Leitor</title>
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
                    $nome = isset($_POST['nome']) ? $_POST['nome'] : "";
                    $telefone = isset($_POST['telefone']) ? $_POST['telefone'] : "";
                    $endereco = isset($_POST['endereco']) ? $_POST['endereco'] : "";
                    $dn = isset($_POST['dn']) ? $_POST['dn'] : "";
                    $cpf = isset($_POST['cpf']) ? $_POST['cpf'] : "";
                    $email = isset($_POST['email']) ? $_POST['email'] : "";
                    $confirmarEmail = isset($_POST['confirmarEmail']) ? $_POST['confirmarEmail'] : "";
                    $senha = isset($_POST['senha']) ? $_POST['senha'] : "";
                    ?>
                </form>
                <form method="post" class="geekcb-form-contact" id="leitorForm">
                    <?php require_once("mensagem.php") ?>
                    <h1 class="titulo">Cadastrar Leitor</h1>
                    <div class="form-row">
                        <div class="form-column; esquerda">
                            <select class="geekcb-field" name="status" id="selectbox" data-selected="">
                                <option class="fonte-status" value="" disabled="disabled" placeholder="Status">Status
                                </option>
                                <option value="Ativo" selected="selected">Ativo</option>
                                <option value="Inativo">Inativo</option>
                                <option value="Pendente">Pendente</option>
                            </select>
                        </div>
                        <div class="form-column">
                            <input class="geekcb-field" value="<?= $dn ?>" id="dn" placeholder="Data de Nascimento"
                                required type="date" name="dn">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <input class="geekcb-field" value="<?= $nome ?>" placeholder="Nome" required type="texto"
                                name="nome">
                        </div>

                        <div class="form-column">
                            <input class="geekcb-field" value="<?= $telefone ?>" id="telefone" name="telefone"
                                placeholder="Telefone" required type="texto" name="telefone">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <input class="geekcb-field" value="<?= $cpf ?>" id="cpf" placeholder="Cpf" required
                                type="texto" name="cpf">
                            <?php echo $cpfInvalido; ?>
                        </div>
                        <div class="form-column">
                            <input class="geekcb-field" value="<?= $endereco ?>" placeholder="Endereço" required
                                type="texto" name="endereco">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <input class="geekcb-field" value="<?= $email ?>" placeholder="E-mail" required type="email"
                                name="email">
                            <?php echo $emailRepetido; ?>
                        </div>
                        <div class="form-column">
                            <input class="geekcb-field" value="<?= $confirmarEmail ?>" placeholder="Confirmar E-mail"
                                required type="email" name="confirmarEmail">
                            <?php echo $emailInvalido; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <input class="geekcb-field" placeholder="Senha" value="<?= $senha ?>" required type="password" name="senha">
                            <?php echo $senhaPequena; ?>
                        </div>
                        <div class="form-column">
                            <input class="geekcb-field" placeholder="Confirmar Senha" required type="password"
                                name="confirmarSenha">
                            <?php echo $senhaInvalido; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda" style="width: 70%">
                            <a href="listarLeitor.php" class="botaolistar" style="padding: 7.45px 8px"><i
                                    class="fa-regular fa-file-lines"></i></a>
                        </div>
                        <div class="form-column">
                            <button class="geekcb-btn" type="submit" name="cadastrar">Cadastrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php require_once("procurarEmprestimo.php") ?>
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