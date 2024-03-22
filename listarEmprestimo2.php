<?php


//1. Conecta no banco de dados (IP, usuario, senha, nome do banco)
//require_once("verificaautenticacao.php");
require_once("conexao.php");

require_once("admAutenticacao.php");

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

while ($linhaVerificarAtraso = mysqli_fetch_array($resultadoVerificarAtraso)) {
    $idEmprestimo = $linhaVerificarAtraso['id'];

    $sqlItemAtrasado = "SELECT 1 FROM itensdeemprestimo WHERE idEmprestimo = $idEmprestimo AND NOW() > dataPrevDev";
    $resultadoItemAtrasado = mysqli_query($conexao, $sqlItemAtrasado);

    if (mysqli_num_rows($resultadoItemAtrasado) > 0) {
        // Pelo menos um item de empréstimo está atrasado, atualiza o status
        $sqlAtualizarStatusEmprestimo = "UPDATE emprestimo SET statusEmprestimo = 'Em atraso' WHERE id = $idEmprestimo";
        mysqli_query($conexao, $sqlAtualizarStatusEmprestimo);
    }
}

$voltar = "";

// Excluir
if (isset($_GET['id'])) { // Verifica se o botão excluir foi clicado
    $sql = "delete from emprestimo where id = " . $_GET['id'];
    mysqli_query($conexao, $sql);
    $mensagem = "Exclusão realizada com sucesso.";
}

$V_WHERE = "";
if (isset($_POST['pesquisar'])) { // botao pesquisar
    $V_WHERE = " and leitor.nome like '% " . $_POST['pesquisar'] . "%' ";
    $voltar = '<a href="listarLivros.php"><button name="voltar" stype="button" class="botaopesquisar">Voltar</button></a>';
}

if (isset($_GET['mensagemAlert'])) {
    $mensagemAlert = $_GET['mensagemAlert'];
}

if(isset($_POST['andamento'])){
    $sql = "SELECT emprestimo.id, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
    FROM emprestimo 
    LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id     
    WHERE statusEmprestimo = 'Em andamento'" . $V_WHERE;

    //3. Executa a SQL
    $resultado = mysqli_query($conexao, $sql);
}elseif(isset($_POST['atraso'])){
    $sql = "SELECT emprestimo.id, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
    FROM emprestimo 
    LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id     
    WHERE statusEmprestimo = 'Em atraso'" . $V_WHERE;

    //3. Executa a SQL
    $resultado = mysqli_query($conexao, $sql);
}elseif(isset($_POST['finalizado'])){
    $sql = "SELECT emprestimo.id, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
    FROM emprestimo 
    LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id     
    WHERE statusEmprestimo = 'Finalizado'" . $V_WHERE;

    //3. Executa a SQL
    $resultado = mysqli_query($conexao, $sql);
}
elseif(isset($_POST['todos'])){
    $sql = "SELECT emprestimo.id, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
    FROM emprestimo 
    LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id     
    ORDER BY FIELD(statusEmprestimo, 'Em atraso', 'Em andamento', 'Finalizado'), id DESC" . $V_WHERE;

    //3. Executa a SQL
    $resultado = mysqli_query($conexao, $sql);
}
else{

//2. Preparar a sql
$sql = "SELECT emprestimo.id, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
        FROM emprestimo 
        LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id     
        ORDER BY FIELD(statusEmprestimo, 'Em atraso', 'Em andamento', 'Finalizado'), id DESC" . $V_WHERE;

//3. Executa a SQL
$resultado = mysqli_query($conexao, $sql);
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
    <link rel="stylesheet" href="css/acervo.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="shortcut icon" href="logo.ico">

    <title>Listar Empréstimos</title>
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
        <br><br><br>
        <?php require_once("mensagem.php"); ?>
        <h1 class="titulo text">Listagem de empréstimos<a href="emprestimo.php" class="botao">
                <i class="fa-solid fa-plus"></i>
            </a></h1>
            <center>
                <br>
            <form method="post">     
            <button type="submit" name="andamento" value="andamento" class="botao">Em andamento</button>
            <button type="submit" name="atraso" value="atraso" class="botao">Em atraso</button>
            <button type="submit" name="finalizado" value="finalizado" class="botao">Finalizado</button>
            <button type="submit" name="todos" value="todos" class="botao">Todos</button>
            </form>
        </center>
        <br><br>


        <center>
            <form method="post">
                <label name="pesquisa" for="exampleFormControlInput1" class="titulo text">Pesquisar</label>
                <div class="input-button-container">
                    <input name="pesquisa" type="text" class="formcampo">
                    <button name="pesquisar" stype="button" class="botaopesquisar"><i
                            class="fa-solid fa-magnifying-glass"></i></button>
                    <?php echo $voltar; ?>
                </div>
                <br><br>
            </form>
        </center>

        <center>
            <div class="card cardlistar">
                <div class="card-body cardlistar2">
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col"><b>ID</b></td>
                                <td scope="col"><b>Status</b></td>
                                <td scope="col"><b>Leitor</b></td>
                                <td scope="col"><b>Data do Emp.</b></td>
                                <td scope="col"><b>Data prevista dev.</b></td>
                                <td scope="col"><b>Livros</b></td>
                                <td scope="col"><b>Multa</b></td>
                                <td scope="col"><b>Ações</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($linha = mysqli_fetch_array($resultado)) { ?>
                                <tr>
                                    <td>
                                        <?= $linha['id'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['statusEmprestimo'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['nomeLeitor'] ?>
                                    </td>
                                    <td>
                                        <?= date("d/m/Y", strtotime($linha['dataEmprestimo'])); ?>
                                    </td>
                                    <td>
                                        <?= date("d/m/Y", strtotime($linha['dataPrevistaDevolucao'])) ?>
                                    </td>
                                    <td>
                                        <?php
                                        $idEmprestimo = $linha['id'];
                                        $sqlLivros = "SELECT distinct livro.titulo FROM itensdeemprestimo
                                            JOIN livro ON itensdeemprestimo.idLivro = livro.id
                                            WHERE itensdeemprestimo.idEmprestimo = $idEmprestimo";

                                        $resultadoLivros = mysqli_query($conexao, $sqlLivros);

                                        $titulosLivros = array();
                                        while ($linhaLivro = mysqli_fetch_array($resultadoLivros)) {
                                            $titulosLivros[] = $linhaLivro['titulo'];
                                        }

                                        echo implode(', <br>', $titulosLivros);
                                        ?>
                                    </td>
                                    <td>
                                        <?= $linha['valorMulta'] ?>
                                    </td>
                                    <td>
                                        <a href="itensDeEmprestimo.php? id=<?= $linha['id'] ?>" style="margin-right: 8px;"
                                            name="info" class="botao">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal_<?= $linha['id'] ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title fs-5" id="exampleModalLabel">
                                                    <?php echo "Leitor " . $linha['nomeLeitor']; ?>
                                                </h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
        <?php require_once('procurarEmprestimo.php') ?>
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
    <script src="js/bootstrap.bundle.js"></script>
</body>

</html>