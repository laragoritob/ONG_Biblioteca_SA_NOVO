<?php
    session_start();
    require_once 'conexao.php';

    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head> 
         <meta charset="UTF-8">
         <title> ONG Bilbioteca - Gestor </title>
         <link rel ="stylesheet" type="text/css" href="css/style.css" />
         <script src="javascript/JS_Logout.js" defer></script>
    </head>
    <body> 
        <header> 
            <h1> Bem-Vindo, <?php echo $_SESSION['usuario']?>! </h1>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout">🚶🏻‍♂️ Logout</a>
            </form>
        </header>
        <ul class="nav-bar">
            <li><a href="#" class="dropbtn"> Início </a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Funcionários </a>
                <div class="dropdown-content">
                    <a href="subtelas/cadastro_funcionario.php"> Registrar Funcionário </a>
                    <a href="subtelas/telconsultar_funcionario.php"> Consultar Funcionários </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Livros </a>
                <div class="dropdown-content">
                    <a href="subtelas/registrar_livro.php"> Registrar Livro </a>
                    <a href="subtelas/consultar_livro.php"> Consultar Livros </a>
                    <a href="subtelas/registrar_autor.php"> Registrar Autor </a>
                    <a href="subtelas/consultar_autor.php"> Consultar Autores </a>
                    <a href="subtelas/registrar_editora.php"> Registrar Editora </a>
                    <a href="subtelas/consultar_editora.php"> Consultar Editoras </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Doador </a>
                <div class="dropdown-content">
                    <a href="subtelas/registrar_doador.php"> Registrar Doadore </a>
                    <a href="subtelas/consultar_doador.php"> Consultar Doadores </a>
                </div>
            </li>
        </ul>
    </body>
    </html>
