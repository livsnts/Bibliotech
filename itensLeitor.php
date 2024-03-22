<?php

require_once("conexao.php");

require_once("leitorAutenticacao.php");

$multa = "";
$V_WHERE = "";

if(isset($_POST['pesquisar'])) {
    $V_WHERE = " AND livro.titulo LIKE '%".$_POST['pesquisa']."%' ";
}
$idEmprestimo = $_GET['id'];

if(isset($_GET['mensagem'])) {
    $mensagem = $_GET['mensagem'];
}

$sqlNome = "SELECT leitor.nome, leitor.id AS idLeitor FROM emprestimo 
            INNER JOIN leitor ON emprestimo.idLeitor = leitor.id
            WHERE emprestimo.id = $idEmprestimo";


$sqlData = "SELECT dataEmprestimo FROM emprestimo 
             WHERE id = ".$idEmprestimo;

$nomeLeitor = mysqli_query($conexao, $sqlNome);
$dataEmprestimo = mysqli_query($conexao, $sqlData);
$linhaLeitor = mysqli_fetch_assoc($nomeLeitor);

$livrosSelecionados = array();


if(isset($_POST['renovar'])) {

    if(isset($_POST['idLivro']) && is_array($_POST['idLivro'])) {
        $livrosSelecionados = $_POST['idLivro'];
        // Percorre os livros selecionados
        foreach($livrosSelecionados as $idLivro) {

            $consultaQuantRenov = "SELECT quantRenov FROM itensdeemprestimo WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            $resultadoConsulta = mysqli_query($conexao, $consultaQuantRenov);

            $dados = mysqli_fetch_assoc($resultadoConsulta);
            $quantRenov = $dados['quantRenov'] + 1;

            // Atualiza o banco de dados com o novo valor de quantRenov
            $atualizaQuantRenov = "UPDATE itensdeemprestimo SET quantRenov = $quantRenov WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            mysqli_query($conexao, $atualizaQuantRenov);


            $dataAtual = date("Y-m-d");

            if($quantRenov <= 2) {
                // Realiza a atualização no banco de dados para marcar como renovado
                $sql = "UPDATE itensdeemprestimo SET statusItem = 'Renovado', dataRenovacao = '$dataAtual', quantRenov = $quantRenov WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
                mysqli_query($conexao, $sql);

                // Atualiza a data prevista de devolução para 7 dias após a renovação
                $dataPrevDev = date('Y-m-d', strtotime("+7 days", strtotime($dataAtual)));
                $sqlDataDev = "UPDATE itensdeemprestimo SET dataPrevDev = '$dataPrevDev' WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
                mysqli_query($conexao, $sqlDataDev);

                $mensagem = "Renovado com sucesso";
            } else {
                $mensagemRenovacao = "O livro já foi renovado duas vezes.";
            }
        }
    }
}


//2. Preparar a sql
$sql = "SELECT distinct emprestimo.id as idEmprestimo, livro.titulo as titulo, statusItem, dataDevolvido, dataPrevDev as dataPrevista, livro.id as idLivro
        FROM itensDeEmprestimo 
        INNER JOIN livro ON itensDeEmprestimo.idLivro = livro.id 
        LEFT JOIN emprestimo ON itensDeEmprestimo.idEmprestimo = emprestimo.id     
        WHERE idEmprestimo = $idEmprestimo".$V_WHERE;

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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Renovação</title>
</head>

<body style="background-color: #ffd8be">

    <section class="dashboardLeitor">
        <a href="principal.php" style="text-decoration: none">
            <h1 class="tituloLeitor text"> <img src="logobiblio.png" alt="logo" width="70px"> Bibliotech</h1><br>
        </a>
        <br>
        <h1 class="titulo text">Itens de Empréstimo</h1>
        <center>
            <form method="post">
                <input type="hidden" name="idEmprestimo" value="<?php echo $_GET['id'] ?>">
                <?php echo $multa; ?>
                <br><br>
              
                    <div class="card cardlistar table-responsive" style="max-width: fit-content; margin: 0; padding: 0">
                        <div class="card-body cardlistar2">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <div style="display: flex; justify-content: space-between;">
                                                <h5 style="margin-right: ">Leitor(a):
                                                    <?php
                                                    echo $linhaLeitor['nome']; ?>
                                                </h5>
                                                <h5>Data Emprestimo
                                                    <?php $linhaData = mysqli_fetch_assoc($dataEmprestimo);
                                                    $dataEmpres = date("d/m/Y", strtotime($linhaData['dataEmprestimo']));
                                                    echo $dataEmpres ?>
                                                </h5>
                                            </div>
                                        </tr>
                                        <?php require_once("mensagem.php"); ?>
                                        <tr>
                                            <td scope="col"><b>Item</b></td>
                                            <td scope="col"><b>Status</b></td>
                                            <td scope="col"><b>Data Prevista</b></td>
                                            <td scope="col"><b>Selecionar livro</b></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($linha = mysqli_fetch_array($resultado)) { ?>
                                            <tr>

                                                <input type="hidden" name="idEmprestimo"
                                                    value="<?= $linha['idEmprestimo'] ?>">
                                                <td>
                                                    <?= $linha['titulo'] ?>
                                                </td>
                                                <td>
                                                    <?= $linha['statusItem'] ?>
                                                </td>
                                                <td>
                                                    <?= date("d/m/Y", strtotime($linha['dataPrevista'])) ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $checked = "";
                                                    if($linha['statusItem'] == 'Devolvido') {
                                                        $checked = 'disabled';
                                                    } ?>
                                                    <input class="form-check-input" type="checkbox" name="idLivro[]"
                                                        value=" <?= $linha['idLivro'] ?>" id="flexCheckIndeterminate" <?php echo $checked ?>>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="exampleModal4" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h2 class="modal-title fs-5" id="exampleModalLabel">Finalizar
                                                            </h2>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form method="post">
                                                            <div class="modal-body">
                                                                <input type="hidden" id="idEmprestimo" name="idEmprestimo"
                                                                    value="<?= $linha['idEmprestimo'] ?>">
                                                                <label for="">Para finalizar o empréstimo, pressione:
                                                                </label>
                                                                <input class="form-check-input" type="checkbox" value=""
                                                                    id="flexCheckIndeterminate" name="check">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Fechar</button>
                                                                <button type="submit" name="finalizar"
                                                                    class="btn btn-danger"
                                                                    data-bs-dismiss="modal">Finalizar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <button name="renovar" type="submit" class="botaopesquisar"
                              style="margin-top: 10pt">Renovar</button> 
                            </div> 
            </form>
           
            </div>
            
        </center>
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