<?php
require_once("conexao.php");

if (isset($_POST["tipo"])) {
    if ($_POST["tipo"] == "autor") {
        $sql = "
                SELECT * FROM autor
                ORDER BY nome ASC
                ";
        $estados = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($autor)) {
            $saida[] = array(
                'id' => $row["id"],
                'nome' => $row["nome"]
            );
        }
        echo json_encode($saida);
    } else {
        $cat_id = $_POST["cat_id"];
        $sql = "
                SELECT * FROM autor 
                WHERE estado = '" . $cat_id . "' 
                ORDER BY nome ASC
                ";
        $cidades = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($autor)) {
            $saida[] = array(
                'id' => $row["id"],
                'nome' => $row["nome"]
            );
        }
        echo json_encode($saida);
    }
}
?>