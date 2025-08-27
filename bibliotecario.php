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
         <title> ONG Bilbioteca - Bibliotecário </title>
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
                <a href="javascript:void(0)" class="dropbtn"> Clientes </a>
                <div class="dropdown-content">
                    <a href="subtelas/cadastro_cliente.php"> Registrar Cliente </a>
                    <a href="subtelas/consultar_cliente.php"> Consultar Clientes </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Livros </a>
                <div class="dropdown-content">
                    <a href="subtelas/consultar_livro.php"> Consultar Livros </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Empréstimos </a>
                <div class="dropdown-content">
                    <a href="subtelas/registro_emprestimo.php"> Registrar Empréstimo </a>
                    <a href="subtelas/consultar_emprestimo.php"> Consultar Empréstimos </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Doador </a>
                <div class="dropdown-content">
                    <a href="subtelas/registro_doador.php"> Registrar Doador </a>
                    <a href="subtelas/consultar_doador.php"> Consultar Doadores </a>
                </div>
            </li>
        </ul>
    </body>
    </html>
