<?php
//1. Conecta no banco de dados (IP, usuario, senha, nome do banco)
//require_once("verificaautenticacao.php");
require_once("conexao.php");

require_once("leitorAutenticacao.php");

$voltar = "";

// Excluir

$V_WHERE = "";
if(isset($_GET['mensagemAlert'])) {
    $mensagemAlert = $_GET['mensagemAlert'];
}

//2. Preparar a sql
$sql = "SELECT emprestimo.id as idEmprestimo, leitor.nome as nomeLeitor, statusEmprestimo, dataEmprestimo, dataPrevistaDevolucao, valorMulta
        FROM emprestimo 
        LEFT JOIN leitor ON emprestimo.idLeitor = leitor.id  
        WHERE idLeitor = $_SESSION[id] and 
        statusEmprestimo = 'Em andamento'".$V_WHERE;

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

        <a href="principal.php" style="text-decoration: none">
            <h1 class="tituloLeitor text"> <img src="logobiblio.png" alt="logo" width="70px"> Bibliotech</h1><br>
        </a>
        <br>
        <?php require_once("mensagem.php"); ?>
        <h1 class="titulo text">Empréstimos disponíveis para renovação</h1>

        <br><br>



        <center>
            <?php while($linha = mysqli_fetch_array($resultado)) {

                if($linha['statusEmprestimo'] == 'Em andamento') { ?>
                    <div class="wrapperAcervo" style="height: 400px">

                        <div class="containerAcervo"
                            style="border: 5px solid #9381ff; padding: 10px; border-radius: 10px 10px 10px 10px;width: 290px; height: 400px">
                            <div class="topAcervo">



                                <div class="contentsAcervo">
                                    <table
                                        style="padding-top: 10%; padding-left: 5%; padding-right: 5%;text-align:left; margin-left: 1rem width:90%">
                                        <tr>
                                            <th>Código: </th>
                                            <td>
                                                <?= $linha['idEmprestimo'] ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>Status: </th>
                                            <td>
                                                <?= $linha['statusEmprestimo'] ?>
                                            </td>

                                        </tr>

                                        <tr>

                                            <th>Data do empréstimo</th>
                                            <td>
                                                <?= date("d/m/Y", strtotime($linha['dataEmprestimo'])); ?>
                                            </td>

                                        </tr>
                                        <tr>

                                            <th>Data prevista para dev.: </th>
                                            <td>
                                                <?= date("d/m/Y", strtotime($linha['dataPrevistaDevolucao'])) ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Livros: </th>

                                            <td>
                                                <?php
                                                $idEmprestimo = $linha['idEmprestimo'];
                                                $sqlLivros = "SELECT distinct livro.titulo FROM itensdeemprestimo
                                                JOIN livro ON itensdeemprestimo.idLivro = livro.id
                                                WHERE itensdeemprestimo.idEmprestimo = $idEmprestimo";

                                                $resultadoLivros = mysqli_query($conexao, $sqlLivros);

                                                $titulosLivros = array();
                                                while($linhaLivro = mysqli_fetch_array($resultadoLivros)) {
                                                    $titulosLivros[] = $linhaLivro['titulo'];
                                                }

                                                echo implode(', <br>', $titulosLivros);
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="padding-left: 40%; padding-top: 12%">
                                        <a href="itensLeitor.php? id=<?= $linha['idEmprestimo'] ?>" style="margin-right: 8px"
                                                    name="info" class="botao">
                                                    <i class="fa-solid fa-eye"></i></a>
                                                </td>
                                                
                                            </tr>
                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php }
            } ?>

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