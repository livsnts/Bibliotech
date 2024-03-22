<?php
//1. Conecta no banco de dados (IP, usuario, senha, nome do banco)
//require_once("verificaautenticacao.php");
require_once("conexao.php");

require_once("admAutenticacao.php");

$voltar = '';
// Excluir
if(isset($_POST['excluir'])) { // Verifica se o botão excluir foi clicado
    $idLivro = $_POST['idLivro'];

    $sqlVerificarEmprestimo = "SELECT * FROM itensdeemprestimo WHERE idLivro = ".$idLivro;
    $resultadoVerificarEmprestimo = mysqli_query($conexao, $sqlVerificarEmprestimo);

    if(mysqli_num_rows($resultadoVerificarEmprestimo) > 0) {
        // O leitor possui empréstimos pendentes, não permitir a exclusão
        $mensagemAlert = "Não é possível excluir o Livro. Há empréstimos cadastrados com ele.";
    } else {
        // Não existem empréstimos pendentes, prosseguir com a exclusão

        $sqlExcluirLivroAutor = "DELETE FROM livroautor WHERE idLivro = $idLivro";
        mysqli_query($conexao, $sqlExcluirLivroAutor);

        $sqlExcluirLivro = "DELETE FROM livro WHERE id = ".$idLivro;
        mysqli_query($conexao, $sqlExcluirLivro);

        $mensagem = "Exclusão realizada com sucesso.";
    }
}


$V_WHERE = "";
$filtroGenero = "";
$filtroEditora = "";
$filtroStatus = "";

if(isset($_POST['pesquisar'])) { // botao pesquisar
    $V_WHERE = " and titulo like '%".$_POST['titulo']."%' ";
    $voltar = '<a href="listarLivros.php"><button name="voltar" stype="button" class="botaopesquisar">Voltar</button></a>';
}

if(isset($_POST['filtro'])) {
    if(isset($_POST['idGenero'])) {
        $genero = $_POST['idGenero'];
        $filtroGenero = " and genero.id = ".$genero;
    }
    if(isset($_POST["idEditora"])) {
        $editora = $_POST['idEditora'];
        $filtroEditora = " and editora.id = ".$editora;
    }
    if(isset($_POST["statusLivro"])) {
        $statusLivroFiltro = $_POST['statusLivro'];
        $filtroStatus = " and livro.statusLivro = '".$statusLivroFiltro . "'";

    }
}

//2. Preparar a sql
$sql = "SELECT livro.id, editora.nome as nomeEditora, genero.nome as nomeGenero, livro.statusLivro, livro.titulo, livro.pag, livro.isbn, livro.edicao, livro.arquivo as arquivo
        FROM livro
        LEFT JOIN editora ON livro.idEditora = editora.id
        LEFT JOIN genero ON livro.idGenero = genero.id
        WHERE 1 = 1".$V_WHERE.$filtroGenero.$filtroEditora.$filtroStatus;

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

    <title>Listar Livros</title>
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
        <?php require_once("mensagem.php") ?>
        <h1 class="titulo text">Listagem de Livros <a href="cadastrarLivro.php" class="botao">
                <i class="fa-solid fa-plus"></i>
            </a></h1>

        <br><br>


        <center>
            <form method="post">
                <label name="titulo" for="exampleFormControlInput1" class="titulo text">Pesquisar</label>
                <div class="input-button-container">
                    <input name="titulo" type="text" class="formcampo">
                    <button name="pesquisar" stype="button" class="botaopesquisar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <?= $voltar ?>
                </div>
                <br><br>
            </form>
        </center>
        <center>




        </center>
        <center>
            <div class="card cardlistar">
                <div class="card-body cardlistar2">
                    <form method="post">
                        <div class="form-row">
                            <div class="form-column esquerda">
                                <select class="geekcb-field" name="idEditora" id="selectbox" data-selected="">
                                    <option class="fonte-status" value="" disabled="disabled" <?php echo empty($_POST['idEditora']) ? 'selected="selected"' : ''; ?>
                                        placeholder="Editora">
                                        Editora</option>
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
                            <div class="form-column">
                                <table>
                                    <tr>
                                        <td style="padding-right: 50px;">
                                            <select class="geekcb-field" name="idGenero" id="selectbox"
                                                data-selected="">
                                                <option class="fonte-status" value="" disabled="disabled"
                                                    placeholder="Gênero" <?php echo empty($_POST['idGenero']) ? 'selected="selected"' : ''; ?>>
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
                                        </td>
                                        <td>
                                            <select class="geekcb-field" name="statusLivro" id="selectbox"
                                                data-selected="">
                                                <option value="" class="fonte-status" disabled selected hidden>Selecione
                                                    o Status</option>
                                                <option value="Disponível" <?= (isset($_POST['statusLivro']) && $_POST['statusLivro'] == 'Disponível') ? 'selected="selected"' : '' ?>>Disponível</option>
                                                <option value="Emprestado" <?= (isset($_POST['statusLivro']) && $_POST['statusLivro'] == 'Emprestado') ? 'selected="selected"' : '' ?>>Emprestado</option>
                                            </select>
                                        </td>
                                        <td>
                                            <button name="filtro" stype="button" class="botaopesquisar">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
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
                    <table class="table">
                        <thead>
                            <tr>
                                <td scope="col"><b>ID</b></td>
                                <td scope="col"><b>Editora</b></td>
                                <td scope="col"><b>Gênero</b></td>
                                <td scope="col"><b>Status</b></td>
                                <td scope="col"><b>Título</b></td>
                                <td scope="col"><b>Página</b></td>
                                <td scope="col"><b>ISBN</b></td>
                                <td scope="col"><b>Edição</b></td>
                                <td scope="col"><b>Ações</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($linha = mysqli_fetch_array($resultado)) { ?>
                                <tr>
                                    <td>
                                        <?= $linha['id'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['nomeEditora'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['nomeGenero'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['statusLivro'] ?>
                                    </td>
                                    <td style="word-wrap: break-word;">
                                        <?= $linha['titulo'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['pag'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['isbn'] ?>
                                    </td>
                                    <td>
                                        <?= $linha['edicao'] ?>
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <a style="margin-right: 8px;" href="alterarLivro.php? id=<?= $linha['id'] ?>"
                                                class="botao">
                                                <i class="fa-solid fa-pen-to-square"></i></a>

                                            <a data-bs-toggle="modal" data-bs-target="#exampleModal_<?= $linha['id'] ?>"
                                                style="margin-right: 8px;" name="info" class="botao">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="listarLivros.php? id=<?= $linha['id'] ?>" class="botao"
                                                data-bs-toggle="modal" data-bs-target="#modalExcluir<?= $linha['id'] ?>">
                                                <i class=" fa-sharp fa-solid fa-trash"></i> </a>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="exampleModal_<?= $linha['id'] ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title fs-5" id="exampleModalLabel">
                                                    <?php echo "Livro ".$linha['titulo']; ?>
                                                </h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?php echo "uploads/".$linha['arquivo'] ?>" alt=""
                                                    style="width: 200px; height: auto;">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal fade" id="modalExcluir<?= $linha['id'] ?>" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="modal-title fs-5" id="exampleModalLabel">Excluir Livro
                                                </h2>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form method="post">
                                                <div class="modal-body">
                                                    <input type="hidden" id="idLivro" name="idLivro"
                                                        value="<?= $linha['id'] ?>">
                                                    <label for="">Para excluir o Livro
                                                        <?= $linha['titulo'] ?>, pressione:
                                                    </label>
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="flexCheckIndeterminate" name="check">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Fechar</button>
                                                    <button type="submit" name="excluir" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Excluir Livro</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </center>
        <?php require_once("procurarEmprestimo.php"); ?>
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