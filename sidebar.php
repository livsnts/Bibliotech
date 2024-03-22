<li><a data-bs-toggle="modal" data-bs-target="#modalProcurarEmprestimo">
        <i class="fa-solid fa-arrows-left-right-to-line"></i><span class="link-name">Devolver</span>
    </a>
</li>

<li><a class="cadastrar-btn">
        <i class="fa-solid fa-cash-register"></i><span class="link-name">Cadastrar</span></a>
    <ul class="cadastrar-show">
        <li><a href="emprestimo.php" class="text">Emprestimo</a></li>
        <li><a href="cadastrarLeitor.php" class="text">Leitor</a></li>
        <li><a href="testeCadastrarLivro.php" class="text">Livro</a></li>
        <li><a href="cadastrarAutor.php" class="text">Autor</a></li>
        <li><a href="cadastrarAdministrador.php" class="text">Administrador</a></li>
        <li><a href="cadastrarGenero.php" class="text">Gênero</a></li>
        <li><a href="cadastrarEditora.php" class="text">Editora</a></li>
    </ul>
</li>


<li><a class="listar-btn">
        <i class="fa-solid fa-list"></i><span class="link-name">Listar</span></a>
    <ul class="listar-show">
        <li><a href="listarEmprestimo2.php" class="text">Emprestimo</a></li>
        <li><a href="listarLeitor.php" class="text">Leitor</a></li>
        <li><a href="listarLivros.php" class="text">Livro</a></li>
        <li><a href="listarAutores.php" class="text">Autor</a></li>
        <li><a href="listarAdministrador.php" class="text">Administrador</a></li>
        <li><a href="listarGenero.php" class="text">Gênero</a></li>
        <li><a href="listarEditora.php" class="text">Editora</a></li>
    </ul>
</li>

<script>

    $('.cadastrar-btn').click(function () {
        $('nav ul .cadastrar-show').toggleClass("show");
        $('nav ul .listar-show').removeClass("show");
    })
    $('.listar-btn').click(function () {
        $('nav ul .listar-show').toggleClass("show");
        $('nav ul .cadastrar-show').removeClass("show");
    })
</script>