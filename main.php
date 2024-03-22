<?php
require_once("admAutenticacao.php");
require_once("navbar.php");

$voltar = "";
if (isset($_POST['pesquisar'])) {
    $voltar = '<a href="main.php" style="text-decoration: none"><button name="voltar" stype="button" class="botaopesquisarAcervo">Voltar</button></a>';
}
?>

<section class="home-section">
    <br><br>
    <h1 class="titulo text"> <img src="logobiblio.png" alt="logo" width="5%"> Bibliotech</h1>

    <center>
        <form method="post">
            <label name="pesquisa" for="exampleFormControlInput1" class="titulo text">Pesquisar livros no acervo da
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
    <div class="acervocontainer">
    <?php
    require_once("acervo1.php");
    require_once("procurarEmprestimo.php");
    ?>
 </div>
    </div>
    <?php require_once("rodape.php");