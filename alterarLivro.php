<?php
//1. Conectar no BD (IP, usuario, senha, nome do bd)
require_once("conexao.php");

require_once("admAutenticacao.php");

if (isset($_POST['salvar'])) {
    $id = $_POST['id'];
    $idEditora = $_POST['idEditora'];
    $idGenero = $_POST['idGenero'];
    $statusLivro = $_POST['statusLivro'];
    $titulo = $_POST['titulo'];
    $pag = $_POST['pag'];
    $isbn = $_POST['isbn'];
    $edicao = $_POST['edicao'];

    if (isset($_FILES['novaImagem']) && $_FILES['novaImagem']['error'] == UPLOAD_ERR_OK) {
        $diretorio = "uploads/";
        $nomeArquivo = $_FILES['novaImagem']['name'];
        $arquivoDestino = $diretorio . $_FILES['novaImagem']['name'];
        move_uploaded_file($_FILES['novaImagem']['tmp_name'], $arquivoDestino);

        $sql = "UPDATE livro
            SET 
            idEditora = '$idEditora',
            idGenero = '$idGenero',
            statusLivro = '$statusLivro',
            titulo = '$titulo',
            pag = '$pag',
            isbn = '$isbn',
            arquivo = '$nomeArquivo',
            edicao = '$edicao'
            WHERE id = $id";

        // Executar a SQL
        mysqli_query($conexao, $sql);
    } else {
        $sql = "UPDATE livro
            SET 
            idEditora = '$idEditora',
            idGenero = '$idGenero',
            statusLivro = '$statusLivro',
            titulo = '$titulo',
            pag = '$pag',
            isbn = '$isbn',
            edicao = '$edicao'
            WHERE id = $id";

        // Executar a SQL
        mysqli_query($conexao, $sql);
    }


    // Atualizar autores associados ao livro
    if (isset($_POST['autor'])) {
        $autoresSelecionados = $_POST['autor'];

        // Remover autores existentes para substituir pelos novos
        $sqlDelete = "DELETE FROM livroautor WHERE idLivro = $id";
        mysqli_query($conexao, $sqlDelete);

        // Inserir os novos autores
        foreach ($autoresSelecionados as $idAutor) {
            $sqlInsertAutor = "INSERT INTO livroautor (idLivro, idAutor) VALUES ($id, $idAutor)";
            mysqli_query($conexao, $sqlInsertAutor);
        }
    }


    $mensagem = "Alterado com sucesso";
}

//Busca usuário selecionado pelo "usuarioListar.php"
$sql = "select * from livro where id = " . $_GET['id'];
$resultado = mysqli_query($conexao, $sql);
$linha = mysqli_fetch_array($resultado);

// Buscar autores associados ao livro
$sqlAutoresLivro = "SELECT idAutor FROM livroautor WHERE idLivro = " . $_GET['id'];
$resultadoAutoresLivro = mysqli_query($conexao, $sqlAutoresLivro);

$autoresAssociados = array();
while ($linhaAutorLivro = mysqli_fetch_array($resultadoAutoresLivro)) {
    $autoresAssociados[] = $linhaAutorLivro['idAutor'];
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
    <script src="https://kit.fontawesome.com/e507e7a758.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/cadastrar.css">
    <link rel="stylesheet" href="css/bootstrap.css">

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="shortcut icon" href="logo.ico">

    <title>Alterar Livro</title>
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
                    $statusLivro = isset($_POST['statusLivro']) ? $_POST['statusLivro'] : "";
                    $idGenero = isset($_POST['idGenero']) ? $_POST['idGenero'] : "";
                    $idEditora = isset($_POST['idEditora']) ? $_POST['idEditora'] : "";
                    $nome = isset($_POST['titulo']) ? $_POST['titulo'] : "";
                    $pag = isset($_POST['pag']) ? $_POST['pag'] : "";
                    $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : "";
                    $edicao = isset($_POST['edicao']) ? $_POST['edicao'] : "";
                    $arquivo = isset($_POST['arquivo']) ? $_POST['arquivo'] : "";
                    ?>
                </form>

                <form method="post" class="geekcb-form-contact" enctype="multipart/form-data" id="insert_data">
                    <?php require_once("mensagem.php") ?>
                    <input type="hidden" name="id" value="<?= $linha['id'] ?>">
                    <h1 class="titulo">Alterar Livro</h1>
                    <div class="form-row">
                        <div class="form-column; esquerda">
                            <label for="">Status</label>
                            <select class="geekcb-field" name="statusLivro" id="selectbox" data-selected="">
                                <option class="fonte-status" value="" disabled="disabled" placeholder="Status">Status
                                </option>
                                <option value="Disponível" <?= ($linha['statusLivro'] == 'Disponível') ? 'selected="selected"' : '' ?>>Disponível</option>
                                <option value="Emprestado" <?= ($linha['statusLivro'] == 'Emprestado') ? 'selected="selected"' : '' ?>>Emprestado</option>
                            </select>
                        </div>
                        <div class="form-column">
                            <label for="">Título</label>
                            <input class="geekcb-field" id="titulo" value="<?= $linha['titulo'] ?>"
                                placeholder="Título do livro" required type="texto" name="titulo">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <label for="">Quantidade de Páginas</label>
                            <input class="geekcb-field" placeholder="Quantidade de páginas" value="<?= $linha['pag'] ?>"
                                required type="texto" name="pag">
                        </div>

                        <div class="form-column">
                            <label for="">ISBN</label>
                            <input class="geekcb-field" value="<?= $linha['isbn'] ?>" id="isbn" name="isbn"
                                placeholder="ISBN" required type="texto">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <label for="">Edição</label>
                            <input class="geekcb-field" id="edicao" value="<?= $linha['edicao'] ?>" placeholder="Edição"
                                required type="texto" name="edicao">
                        </div>
                        <div class="form-column">
                            <label for="">Gênero</label>
                            <select class="geekcb-field" name="idGenero" id="selectbox" data-selected="">
                                <option class="fonte-status" value="" disabled="disabled" placeholder="Gênero">
                                    Gênero</option>
                                <?php
                                $sql = "select * from genero order by nome";
                                $resultado = mysqli_query($conexao, $sql);

                                while ($linhaGenero = mysqli_fetch_array($resultado)):
                                    $idGenero = $linhaGenero['id'];
                                    $nomeGenero = $linhaGenero['nome'];
                                    $selectedGenero = ($idGenero == $linha['idGenero']) ? 'selected="selected"' : '';

                                    echo "<option value='{$idGenero}' {$selectedGenero}>{$nomeGenero}</option>";
                                endwhile;
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <label for="">Editora</label>
                            <select class="geekcb-field" name="idEditora" id="selectbox" data-selected="">
                                <option class="fonte-status" value="" disabled="disabled" placeholder="Editora">
                                    Editora</option>
                                <?php
                                $sql = "select * from editora order by nome";
                                $resultado = mysqli_query($conexao, $sql);

                                while ($linhaEditora = mysqli_fetch_array($resultado)):
                                    $idEditora = $linhaEditora['id'];
                                    $nomeEditora = $linhaEditora['nome'];
                                    $selectedEditora = ($idEditora == $linhaEditora['idEditora']) ? 'selected="selected"' : '';

                                    echo "<option value='{$idEditora}' {$selectedEditora}>{$nomeEditora}</option>";
                                endwhile;
                                ?>
                            </select>
                        </div>
                        <div class="form-column">
                            <label for="">Autor(es)</label>
                            <select class="geekcb-field" name="autor[]" id="autor" multiple>
                                <option class="fonte-status" disabled="disabled" placeholder="Selecione os autores">
                                </option>
                                <?php
                                $sqlAutores = "SELECT * FROM autor ORDER BY nome";
                                $resultadoAutores = mysqli_query($conexao, $sqlAutores);

                                while ($linhaAutor = mysqli_fetch_array($resultadoAutores)) {
                                    $idAutor = $linhaAutor['id'];
                                    $nomeAutor = $linhaAutor['nome'];

                                    $selected = (in_array($idAutor, $autoresAssociados)) ? 'selected="selected"' : '';

                                    echo "<option value='{$idAutor}' {$selected}>{$nomeAutor}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda">
                            <label for="">Imagem Atual</label>
                            <input type="text" class="geekcb-field" value="<?= $linha['arquivo'] ?>" readonly>
                        </div>
                        <div class="form-column">
                            <label for="">Nova Imagem</label>
                            <input type="file" class="geekcb-field" name="novaImagem" id="novaImagem">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-column esquerda" style="width: 70%">
                            <a href="listarLivros.php" class="botaolistar" style="padding: 7.45px 8px"><i
                                    class="fa-regular fa-file-lines"></i></a>
                        </div>
                        <div class="form-column">
                            <button class="geekcb-btn" type="submit" name="salvar">Salvar</button>
                        </div>
                    </div>
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
    <script>
        $(document).ready(function () {
            $('#autor').select2();
        });
    </script>
</body>

</html>