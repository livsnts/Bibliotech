<?php 

if(isset($mensagem)) {
    echo '<div class="alert alert-success" style="text-align :center" role="alert">' . $mensagem . '</div>';
} 
if(isset($mensagemAlert)) {
    echo '<div class="alert alert-danger" style="text-align :center" role="alert">' . $mensagemAlert . '</div>';
} 
if(isset($mensagemRenovacao)) {
    echo '<div class="alert alert-danger" style="text-align :center" role="alert">' . $mensagemRenovacao . '</div>';
} 
if(isset($mensagemRenovacaoAtraso)) {
    echo '<div class="alert alert-danger" style="text-align :center" role="alert">' . $mensagemRenovacaoAtraso . '</div>';
} 

?>

