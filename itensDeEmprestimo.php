<?php

require_once("conexao.php");

require_once("admAutenticacao.php");

$multa = "";
$V_WHERE = "";

if (isset($_POST['pesquisar'])) {
    $V_WHERE = " AND livro.titulo LIKE '%" . $_POST['pesquisa'] . "%' ";
}
$idEmprestimo = $_GET['id'];

if(isset($_GET['mensagem'])) {
    $mensagem = $_GET['mensagem'];
}

$sqlNome = "SELECT leitor.nome, leitor.id AS idLeitor FROM emprestimo 
            INNER JOIN leitor ON emprestimo.idLeitor = leitor.id
            WHERE emprestimo.id = $idEmprestimo";


$sqlData = "SELECT dataEmprestimo FROM emprestimo 
             WHERE id = " . $idEmprestimo;

$nomeLeitor = mysqli_query($conexao, $sqlNome);
$dataEmprestimo = mysqli_query($conexao, $sqlData);
$linhaLeitor = mysqli_fetch_assoc($nomeLeitor);

$livrosSelecionados = array();

if (isset($_POST['devolver'])) {
    $idEmprestimo = $_POST['idEmprestimo'];
    $dataAtual = date("Y-m-d");

    if (isset($_POST['idLivro']) && is_array($_POST['idLivro'])) {
        $livrosSelecionados = $_POST['idLivro'];

        foreach ($livrosSelecionados as $idLivro) {
            $sqlDataPrevDev = "SELECT dataPrevDev FROM itensdeemprestimo WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            $resultDataPrevDev = mysqli_query($conexao, $sqlDataPrevDev);
            $linhaDataPrevDev = mysqli_fetch_assoc($resultDataPrevDev);

            $dataPrevista = strtotime($linhaDataPrevDev['dataPrevDev']);
            $dataDevolucao = strtotime($dataAtual);

            $diferencaEmDias = ($dataDevolucao - $dataPrevista) / (60 * 60 * 24);

            $multaItem = ($diferencaEmDias > 0) ? $diferencaEmDias * 1 : 0;
                       
            $sql = "UPDATE itensdeemprestimo SET statusItem = 'Devolvido', dataDevolvido = '$dataAtual', multa = '$multaItem' WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            mysqli_query($conexao, $sql);

            $sql = "UPDATE livro SET statusLivro = 'Disponível' WHERE id = $idLivro";
            mysqli_query($conexao, $sql);
                       

            $mensagem = "Devolvido com sucesso";
        }

        $sqlMulta = "SELECT DISTINCT multa FROM itensdeemprestimo WHERE idEmprestimo = $idEmprestimo";
        $resultadoMulta = mysqli_query($conexao, $sqlMulta);

        $multaTotal = 0;

        while ($linhaMulta = mysqli_fetch_assoc($resultadoMulta)) {
            $multaTotal += $linhaMulta['multa'];
        }

        $sqlStatusItem = "SELECT DISTINCT statusItem FROM itensdeemprestimo WHERE idEmprestimo = $idEmprestimo";
        $resultStatusItem = mysqli_query($conexao, $sqlStatusItem);
        $statusItens = mysqli_fetch_all($resultStatusItem, MYSQLI_ASSOC);

        $todosDevolvidos = count($statusItens) == 1 && $statusItens[0]['statusItem'] == 'Devolvido';

        if ($todosDevolvidos) {
            $sql = "UPDATE emprestimo SET statusEmprestimo = 'Finalizado', valorMulta = '$multaTotal' WHERE id = $idEmprestimo";
            mysqli_query($conexao, $sql);

            $idLeitor = $linhaLeitor['idLeitor'];

            $sql = "UPDATE leitor SET status = 'Ativo' WHERE id = $idLeitor";
            mysqli_query($conexao, $sql);

            if ($multaTotal > 0) {
                $mensagemAlert = "Empréstimo finalizado com multa: R$" . $multaTotal;
            } else {
                $mensagem = "Empréstimo finalizado";
            }
        }
    }
}




if (isset($_POST['renovar'])) {
    
    if (isset($_POST['idLivro']) && is_array($_POST['idLivro'])) {
        $livrosSelecionados = $_POST['idLivro'];
        // Percorre os livros selecionados
        foreach ($livrosSelecionados as $idLivro) {

            $sqlDataPrevDev = "SELECT dataPrevDev FROM itensdeemprestimo WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            $resultDataPrevDev = mysqli_query($conexao, $sqlDataPrevDev);
            $linhaDataPrevDev = mysqli_fetch_assoc($resultDataPrevDev);

            $dataPrevista = strtotime($linhaDataPrevDev['dataPrevDev']);
            $dataAtual = strtotime(date("Y-m-d"));

            if ($dataAtual > $dataPrevista) {
                $mensagemRenovacaoAtraso = "O livro não pode ser renovado pois está em atraso.";
                break;  
            }


            $consultaQuantRenov = "SELECT quantRenov FROM itensdeemprestimo WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            $resultadoConsulta = mysqli_query($conexao, $consultaQuantRenov);

            $dados = mysqli_fetch_assoc($resultadoConsulta);
            $quantRenov = $dados['quantRenov'] + 1;

            // Atualiza o banco de dados com o novo valor de quantRenov
            $atualizaQuantRenov = "UPDATE itensdeemprestimo SET quantRenov = $quantRenov WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            mysqli_query($conexao, $atualizaQuantRenov);


            $dataAtual = date("Y-m-d");
            
            if($quantRenov <= 2){
            // Realiza a atualização no banco de dados para marcar como renovado
            $sql = "UPDATE itensdeemprestimo SET statusItem = 'Renovado', dataRenovacao = '$dataAtual', quantRenov = $quantRenov WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            mysqli_query($conexao, $sql);

            // Atualiza a data prevista de devolução para 7 dias após a renovação
            $dataPrevDev = date('Y-m-d', strtotime("+7 days", strtotime($dataAtual)));
            $sqlDataDev = "UPDATE itensdeemprestimo SET dataPrevDev = '$dataPrevDev' WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
            mysqli_query($conexao, $sqlDataDev);

            $mensagem = "Renovado com sucesso";
        } else{
        $mensagemRenovacao = "O livro já foi renovado duas vezes.";
  }
    }
  }
  
}

if (isset($_POST["finalizar"]) && isset($_POST['check'])) {
    $idEmprestimo = $_POST['idEmprestimo'];
    $dataAtual = date("Y-m-d");
    $multaTotal = 0;

    // Verifica se o status do empréstimo não está finalizado
    $sqlStatusEmprestimo = "SELECT statusEmprestimo FROM emprestimo WHERE id = $idEmprestimo";
    $resultStatusEmprestimo = mysqli_query($conexao, $sqlStatusEmprestimo);
    $linhaStatusEmprestimo = mysqli_fetch_assoc($resultStatusEmprestimo);

    if ($linhaStatusEmprestimo['statusEmprestimo'] != 'Finalizado') {
        // Obtém todos os livros relacionados ao empréstimo
        $sqlLivros = "SELECT idLivro, statusItem FROM itensdeemprestimo WHERE idEmprestimo = $idEmprestimo";
        $resultLivros = mysqli_query($conexao, $sqlLivros);

        // Itera sobre os livros para devolvê-los
        while ($linhaLivro = mysqli_fetch_assoc($resultLivros)) {
            $idLivro = $linhaLivro['idLivro'];
            $statusItem = $linhaLivro['statusItem'];

            if ($statusItem != 'Devolvido') {

                $sqlDataPrevDev = "SELECT dataPrevDev FROM itensdeemprestimo WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
                $resultDataPrevDev = mysqli_query($conexao, $sqlDataPrevDev);
                $linhaDataPrevDev = mysqli_fetch_assoc($resultDataPrevDev);

                $dataPrevista = strtotime($linhaDataPrevDev['dataPrevDev']);
                $dataDevolucao = strtotime($dataAtual);

                $diferencaEmDias = ($dataDevolucao - $dataPrevista) / (60 * 60 * 24);

                $multaItem = ($diferencaEmDias > 0) ? $diferencaEmDias * 1 : 0;

                $sql = "UPDATE itensdeemprestimo SET statusItem = 'Devolvido', dataDevolvido = '$dataAtual', multa = '$multaItem' WHERE idLivro = $idLivro AND idEmprestimo = $idEmprestimo";
                mysqli_query($conexao, $sql);

                $sql = "UPDATE livro SET statusLivro = 'Disponível' WHERE id = $idLivro";
                mysqli_query($conexao, $sql);

            }
        }

        // Atualiza o status do empréstimo
        $sql = "UPDATE emprestimo SET statusEmprestimo = 'Finalizado', valorMulta = '$multaTotal' WHERE id = $idEmprestimo";
        mysqli_query($conexao, $sql);

        // Atualiza o status do leitor
        $idLeitor = $linhaLeitor['idLeitor'];
        $sql = "UPDATE leitor SET status = 'Ativo' WHERE id = $idLeitor";
        mysqli_query($conexao, $sql);

        // Calcula a multa total
        $sqlMulta = "SELECT multa FROM itensdeemprestimo WHERE idEmprestimo = $idEmprestimo";
        $resultadoMulta = mysqli_query($conexao, $sqlMulta);
        $multaTotal = 0;

        while ($linhaMulta = mysqli_fetch_assoc($resultadoMulta)) {
            $multaTotal += $linhaMulta['multa'];
        }

        // Exibe mensagem
        if ($multaTotal > 0) {
            $mensagemAlert = "Empréstimo finalizado com multa: R$" . $multaTotal;
        } else {
            $mensagem = "Empréstimo finalizado";
        }
    } else {
        // Empréstimo já finalizado, exiba uma mensagem ou lógica apropriada
        $mensagemAlert = "Empréstimo já finalizado anteriormente.";
    }
}


//2. Preparar a sql
$sql = "SELECT distinct emprestimo.id as idEmprestimo, livro.titulo as titulo, statusItem, dataDevolvido, dataPrevDev as dataPrevista, livro.id as idLivro
        FROM itensDeEmprestimo 
        INNER JOIN livro ON itensDeEmprestimo.idLivro = livro.id 
        LEFT JOIN emprestimo ON itensDeEmprestimo.idEmprestimo = emprestimo.id     
        WHERE idEmprestimo = $idEmprestimo" . $V_WHERE;

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

    <title>Devolução</title>
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
        </div><br><br><br>
        <h1 class="titulo text">Itens de Empréstimo</h1>
        <center>
            <form method="post">
                <input type="hidden" name="idEmprestimo" value="<?php echo $_GET['id'] ?>">
                <?php echo $multa; ?>
                <br><br>
                <div class="card cardlistar">
                    <div class="card-body cardlistar2">

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
                                    <td scope="col"><b>ID do Empréstimo</b></td>
                                    <td scope="col"><b>Item</b></td>
                                    <td scope="col"><b>Status</b></td>
                                    <td scope="col"><b>Data Prevista</b></td>
                                    <td scope="col"><b>Data devolvido</b></td>
                                    <td scope="col"><b>Selecionar livro</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($linha = mysqli_fetch_array($resultado)) { ?>
                                    <tr>
                                        <td>
                                            <?= $linha['idEmprestimo'] ?>
                                        </td>
                                        <input type="hidden" name="idEmprestimo" value="<?= $linha['idEmprestimo'] ?>">
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
                                            <?php isset($linha['dataDevolvido']) ? $data = date("d/m/Y", strtotime($linha['dataDevolvido'])) : $data = "";
                                            echo $data ?>
                                        </td>
                                        <td>
                                            <?php
                                            $checked = "";
                                            if ($linha['statusItem'] == 'Devolvido') {
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
                                                        <button type="submit" name="finalizar" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Finalizar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </tbody>
                        </table>
                        <button name="devolver" type="submit" class="botaopesquisar"
                            style="margin-top: 10pt">Devolver</button>
                        <button name="finalizar" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal4"
                            class="
                    botaopesquisar" style="margin-top: 10pt">Finalizar</button>
                        <button name="renovar" type="submit" class="botaopesquisar"
                            style="margin-top: 10pt">Renovar</button>
            </form>
            </div>
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