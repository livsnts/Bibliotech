<?php 

session_start();

if (!(isset($_SESSION['tipo']) && $_SESSION['tipo'] == "adm")) {

session_destroy();
$mensagemAlert = "É necessário login para acessar a página.";
header("location: index.php?mensagemAlert=$mensagemAlert");

exit();
}
?>