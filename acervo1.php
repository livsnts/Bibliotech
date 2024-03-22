<?php
require_once("conexao.php");

$V_WHERE = "";
if (isset($_POST['pesquisar'])) { // botao pesquisar
    $V_WHERE = " AND livro.titulo LIKE '%{$_POST['pesquisa']}%' ";
    $voltar = '<a href="acervo.php" style="text-decoration: none"><button name="voltar" stype="button" class="botaopesquisarAcervo">Voltar</button></a>';
}

$filtroGenero = "";
$filtroEditora = "";
$filtroStatus = "";

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

if(isset($_POST["reset"])) {
    $filtroGenero = '';
    $filtroEditora = '';
    $filtroStatus = '';
    $_POST['idGenero'] = '';
    $_POST['idEditora'] = '';
    $_POST['statusLivro'] = '';
}

$pagina = 1;

if (isset($_GET['pagina']))
    $pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);

if (!$pagina)
    $pagina = 1;

$limite = 20;
$inicio = ($pagina * $limite) - $limite;

$sql = "SELECT livro.id, editora.nome as nomeEditora, genero.nome as nomeGenero, livro.statusLivro, livro.titulo, livro.pag, livro.isbn, livro.edicao, livro.arquivo as arquivo
        FROM livro
        LEFT JOIN editora ON livro.idEditora = editora.id
        LEFT JOIN genero ON livro.idGenero = genero.id
        WHERE 1 {$V_WHERE}{$filtroGenero}{$filtroEditora}{$filtroStatus}
        ORDER BY livro.id desc LIMIT $inicio, $limite";

$registros = mysqli_fetch_array(mysqli_query($conexao, "SELECT COUNT(titulo) count FROM livro"))['count'];

$paginas = ceil($registros / $limite);


$resultado = mysqli_query($conexao, $sql);

?>


<?php while ($linha = mysqli_fetch_array($resultado)) { ?>


    <div class="wrapperAcervo">

        <div class="containerAcervo">
            <div style="background-image: url('uploads/<?= $linha['arquivo'] ?>'); background-repeat: no-repeat"
                class="topAcervo"></div>
            <div class="bottomAcervo">
                <div class="leftAcervo">
                    <div class="detailsAcervo">
                        <h5 style="width: 50%; margin-top: 5%; text-align:center">
                            <?= $linha['titulo'] ?>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="insideAcervo">
            <div class="icon"><i class="fa-solid fa-plus"></i></div>
            <div class="contentsAcervo">
                <table>
                    <tr>
                        <th>Código do livro: </th>
                        <td>
                            <?= $linha['id'] ?>
                        </td>

                    </tr>

                    <tr>
                        <th>Status do livro: </th>
                        <td>
                            <?= $linha['statusLivro'] ?>
                        </td>

                    </tr>
                    <tr>
                        <th>Autor(es): </th>
                        <td>

                            <?php
                            $idLivro = $linha['id'];
                            $sqlAutores = "SELECT autor.nome FROM livroautor
                                            JOIN autor ON livroautor.idAutor = autor.id
                                            WHERE livroautor.idLivro = $idLivro";

                            $resultadoAutor = mysqli_query($conexao, $sqlAutores);

                            $autores = array();
                            while ($linhaAutor = mysqli_fetch_array($resultadoAutor)) {
                                $autores[] = $linhaAutor['nome'];

                            }
                            echo implode(', ', $autores);

                            ?>

                        </td>
                    </tr>
                    <tr>
                        <th>ISBN: </th>
                        <td>
                            <?= $linha['isbn'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>N° de páginas: </th>
                        <td>
                            <?= $linha['pag'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Gênero: </th>
                        <td>
                            <?= $linha['nomeGenero'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Editora: </th>
                        <td>
                            <?= $linha['nomeEditora'] ?>
                        </td>

                    </tr>

                </table>
            </div>
        </div>
    </div>


<?php } ?>

<br>

<center>
<div class="paginacao text">

    <a href="?pagina=1"> Primeira </a>
    <?php if ($pagina > 1) { ?>
        <a href="?pagina=<?= $pagina - 1 ?>">
        <i class="fa-solid fa-caret-left"></i> </a>
            <?php } ?>

            <?= $pagina ?>

            <?php if ($pagina < $paginas) { ?>
                <a href="?pagina=<?= $pagina + 1 ?>"> <i class="fa-solid fa-caret-right"></i> </a>
            <?php } ?>
            <a href="?pagina=<?= $paginas ?>"> Última </a>
</div>
</center>

<script>
    $('.buyAcervo').click(function () {
        $('.bottomAcervo').addClass("clicked");
    });

    $('.removeAcervo').click(function () {
        $('.bottomAcervo').removeClass("clicked");
    });
</script>