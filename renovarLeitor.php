<?php
//1. Conecta no banco de dados (IP, usuario, senha, nome do banco)
//require_once("verificaautenticacao.php");
require_once("conexao.php");

require_once("leitorAutenticacao.php");

$voltar = "";

// Excluir

$V_WHERE = "";
if (isset($_GET['mensagemAlert'])) {
    $mensagemAlert = $_GET['mensagemAlert'];
}

//2. Preparar a sql
$sql = "SELECT emprestimo.id, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
        FROM emprestimo 
        LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id  
        WHERE idLeitor = $_SESSION[id] and 
        statusEmprestimo = 'Em andamento'" . $V_WHERE;

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
    <link rel="shortcut icon" href="logo.ico">

    <title>Listar Empréstimos</title>
</head>

<body>


    <section class="dashboardLeitor">

    <a href="principal.php" style="text-decoration: none"><h1 class="tituloLeitor text">  <img src="logobiblio.png" alt="logo" width="5%"> Bibliotech</h1><br></a>
        <br>
        <?php require_once("mensagem.php"); ?>
        <h1 class="titulo text">Listagem de empréstimos ativos</h1>

        <br><br>


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
                                        <a href="itensLeitor.php? id=<?= $linha['id'] ?>" style="margin-right: 8px;"
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