<?php

if(isset($_POST['entrar'])):

    //1. pega os dados do usuário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    //2. preparar a SQL
    $sql = "select * 
        from leitor
        where email = '{$email}'
        and senha = '{$senha}'";

    //3. Executa SQL
    require_once("conexao.php");
    $resultado = mysqli_query($conexao, $sql);
    $linhas = mysqli_num_rows($resultado); // retorna o número de linhas da consulta

    //4. Verifica se o usuário existe no BD e concede permissão ou volt ao login

    if($linhas > 0) {

        $usuario = mysqli_fetch_array($resultado);

        if($usuario["status"] != "Inativo") {
            //Cria a sessão para gerar permissao de acesso ao sistema
            session_start();
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo'] = "leitor";

            //Redireciona para o principal
            header("location: principal.php");
        } else {
            $mensagemAlert = "Leitor Inativo";
            header("location: index.php?mensagemAlert=$mensagemAlert");
        }

    } elseif(($linhas == 0)) {
        $sql = "select * 
        from administrador
        where login = '{$email}'
        and senha = '{$senha}'";

        //3. Executa SQL
        require_once("conexao.php");
        $resultado = mysqli_query($conexao, $sql);
        $linhasAdm = mysqli_num_rows($resultado);
        if($linhasAdm > 0) {

            $sql = "select * 
            from administrador
            where login = '{$email}'
            and senha = '{$senha}'";

            $usuario = mysqli_fetch_array($resultado);

            if($usuario["status"] != "Inativo") {
                //Cria a sessão para gerar permissao de acesso ao sistema
                session_start();
                $_SESSION['id'] = $usuario['id'];
                $_SESSION['nome'] = $usuario['nome'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['tipo'] = "adm";

                //Redireciona para o principal
                header("location: main.php");
            } else {
                $mensagemAlert = "Usuário Inativo";
                header("location: index.php?mensagemAlert=$mensagemAlert");
            }
        } else {
            $mensagemAlert = "Usuário/senha inválidos";
            header("location: index.php?mensagemAlert=$mensagemAlert");
        }
    }
endif;

?>