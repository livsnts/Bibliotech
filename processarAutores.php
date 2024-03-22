<?php
require_once("conexao.php");

require_once("admAutenticacao.php");

if (isset($_POST['cadastrarAutorDoLivro'])) {
    $idAutor = $_POST['autor']; // ID do autor selecionado no formulário
    $idLivro = $_POST['idLivro']; // ID do livro que você já cadastrou

    // Insira o par de ID do autor e ID do livro na tabela livroautor
    $sql = "INSERT INTO livroautor (idLivro, idAutor) VALUES ('$idLivro', '$idAutor')";
    mysqli_query($conexao, $sql);

    // Redirecione de volta para a página de cadastro de autores do livro ou para onde você preferir.
    header("Location: cadastrarAutor.php");
}
?>