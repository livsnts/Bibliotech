<?php
//1. Conecta no banco de dados (IP, usuario, senha, nome do banco)
//require_once("verificaautenticacao.php");
require_once("conexao.php");

// Atualiza o status dos empréstimos para 'Em atraso' se houver algum item atrasado
$sqlAtualizarStatus = "UPDATE emprestimo SET statusEmprestimo = 'Em atraso' WHERE id IN (
    SELECT DISTINCT e.id
    FROM emprestimo e
    JOIN itensdeemprestimo ie ON e.id = ie.idEmprestimo
    WHERE NOW() > ie.dataPrevDev AND e.statusEmprestimo = 'Em andamento'
)";

mysqli_query($conexao, $sqlAtualizarStatus);

// Verifica todos os empréstimos e atualiza o status se necessário
$sqlVerificarAtraso = "SELECT id FROM emprestimo WHERE statusEmprestimo = 'Em andamento'";
$resultadoVerificarAtraso = mysqli_query($conexao, $sqlVerificarAtraso);

while($linhaVerificarAtraso = mysqli_fetch_array($resultadoVerificarAtraso)) {
    $idEmprestimo = $linhaVerificarAtraso['id'];

    $sqlItemAtrasado = "SELECT 1 FROM itensdeemprestimo WHERE idEmprestimo = $idEmprestimo AND NOW() > dataPrevDev";
    $resultadoItemAtrasado = mysqli_query($conexao, $sqlItemAtrasado);

    if(mysqli_num_rows($resultadoItemAtrasado) > 0) {
        // Pelo menos um item de empréstimo está atrasado, atualiza o status
        $sqlAtualizarStatusEmprestimo = "UPDATE emprestimo SET statusEmprestimo = 'Em atraso' WHERE id = $idEmprestimo";
        mysqli_query($conexao, $sqlAtualizarStatusEmprestimo);
    }
}
if(isset($_POST["reset"])) {
    $filtroGenero = '';
    $filtroEditora = '';
    $filtroStatus = '';
    $_POST['idGenero'] = '';
    $_POST['idEditora'] = '';
    $_POST['statusLivro'] = '';
}

$voltar = "";

if(isset($_GET['mensagemAlert'])) {
    $mensagemAlert = $_GET['mensagemAlert'];
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

    <title> Bibliotech </title>
</head>

<body style="background-color: #ffd8be;">

    <div style="align-items: right" class="navbar bg-body-tertiary">
        <div style="align-content: right" class="container-fluid">
            <a data-bs-toggle="modal" data-bs-target="#exampleModal"
                style="padding-left: 2%; padding-right: 3%; cursor: pointer; font-family: Fjalla One; color: #9381ff; font-size: 1.4rem">
                <i class="fa-solid fa-user"></i> Login</a>
        </div>
    </div>
    <br><br><br>
    <?php
    if(isset($mensagemAlert)) {
        require_once('mensagem.php');
    }
    if(isset($_POST['pesquisar'])) {
        $voltar = '<a href="acervo.php" style="text-decoration: none"><button name="voltar" stype="button" class="botaopesquisarAcervo">Voltar</button></a>';
    }
    ?>

    <h1 class="titulo"> <img src="logobiblio.png" alt="logo" width="60px"> Bibliotech</h1>



    <center>
        <form method="post">
            <label name="pesquisa" for="exampleFormControlInput1" class="titulo">Pesquisar livros no acervo da
                biblioteca</label>
            <div class="input-button-container">
                <input name="pesquisa" type="text" class="formcampo">
                <button name="pesquisar" stype="button" class="botaopesquisarAcervo2"><i
                        class="fa-solid fa-magnifying-glass"></i>
                </button>
                <?= $voltar; ?>
            </div>
            <br><br>
        </form>
    </center>
    <div style="padding-left: 10%" class="container">
        <form method="post" class="mx-auto">
            <div class="form-row">
                <div class="form-column esquerda">
                    <select class="geekcb-field" name="idEditora" id="selectbox" data-selected="">
                        <option class="fonte-status" value="" disabled="disabled" <?php echo empty($_POST['idEditora']) ? 'selected="selected"' : ''; ?>placeholder="Editora">Editora
                        </option>
                        <?php
                        $sqlEditora = "select * from editora order by nome";
                        $resultadoEditora = mysqli_query($conexao, $sqlEditora);

                        while($linhaEditora = mysqli_fetch_array($resultadoEditora)):
                            $idEditora = $linhaEditora['id'];
                            $nomeEditora = $linhaEditora['nome'];
                            $selectedEditora = ($idEditora == $_POST['idEditora']) ? 'selected="selected"' : '';

                            echo "<option value='{$idEditora}' {$selectedEditora}>{$nomeEditora}</option>";
                        endwhile;
                        ?>
                    </select>
                </div>
                <div class="form-column esquerda">
                    <select class="geekcb-field" name="idGenero" id="selectbox" data-selected="">
                        <option class="fonte-status" value="" disabled="disabled" placeholder="Gênero" <?php echo empty($_POST['idGenero']) ? 'selected="selected"' : ''; ?>>
                            Gênero</option>
                        <?php
                        $sqlGenero = "select * from genero order by nome";
                        $resultadoGenero = mysqli_query($conexao, $sqlGenero);

                        while($linhaGenero = mysqli_fetch_array($resultadoGenero)):
                            $idGenero = $linhaGenero['id'];
                            $nomeGenero = $linhaGenero['nome'];
                            $selectedGenero = ($idGenero == $_POST['idGenero']) ? 'selected="selected"' : '';

                            echo "<option value='{$idGenero}' {$selectedGenero}>{$nomeGenero}</option>";
                        endwhile;
                        ?>
                    </select>
                </div>
                <div class="form-column">
                    <select class="geekcb-field" name="statusLivro" id="selectbox" data-selected="">
                        <option value="" class="fonte-status" disabled selected hidden>Selecione
                            o Status</option>
                        <option value="Disponível" <?= (isset($_POST['statusLivro']) && $_POST['statusLivro'] == 'Disponível') ? 'selected="selected"' : '' ?>>
                            Disponível
                        </option>
                        <option value="Emprestado" <?= (isset($_POST['statusLivro']) && $_POST['statusLivro'] == 'Emprestado') ? 'selected="selected"' : '' ?>>
                            Emprestado
                        </option>
                    </select>
                </div>
                <div class="form-column">
                    <table>
                        <tr>
                            <td>
                                <button name="filtro" stype="button" class="botaopesquisar">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                            </td>
                            <td>
                                <?php
                                if(isset($_POST["filtro"])) {
                                    $reset = '<button name="reset" stype="button" class="botaopesquisar"><i class="fa-solid fa-rotate-left"></i></button>';
                                    echo $reset;
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </form>
    </div>
    <div class="acervocontainer">

        <?php require_once('acervo1.php') ?>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="exampleModalLabel">Login</h2>
                </div>
                <form action="autenticacao.php" method="post">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="geekcb-field" placeholder="E-mail" required type="text" name="email"
                                id="email">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input class="geekcb-field" placeholder="Senha" required type="password" name="senha"
                                id="senha">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="geekcb-btn" type="button" data-bs-dismiss="modal">Fechar</button>
                        <button class="geekcb-btn" type="submit" name="entrar">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="js/script.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
</body>

</html>