<?php
require_once("conexao.php");
if (isset($_POST["procurarEmprestimo"])) {
    $idEmprestimo = $_POST['idEmprestimo'];
    $sql = "select statusEmprestimo from emprestimo where id = $idEmprestimo";
    $resultado = mysqli_query($conexao, $sql);
    if (mysqli_num_rows($resultado) > 0) {
        header("location: itensdeemprestimo.php?id=$idEmprestimo");
    } else {
        // Nenhuma linha encontrada, lide conforme necessário
        $mensagemAlert = "Nenhum empréstimo encontrado com o ID: $idEmprestimo";
        header("location: listarEmprestimo.php?mensagemAlert=$mensagemAlert");
    }
}

?>

<div class="modal fade" id="modalProcurarEmprestimo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="exampleModalLabel">Procurar Empréstimo
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" action="procurarEmprestimo.php">
                <?php require_once("mensagem.php");?>
                <div class="modal-body">
                    <label for="">Código do Empréstimo:
                    </label>
                    <input style="width: 50%" class="geekcb-field" placeholder="Código" required type="texto" name="idEmprestimo">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" name="procurarEmprestimo" class="btn btn-success"
                        data-bs-dismiss="modal">Procurar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="js/bootstrap.bundle.js"></script>