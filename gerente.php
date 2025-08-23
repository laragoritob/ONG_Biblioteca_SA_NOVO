<?php
    session_start();
    require_once 'conexao.php';

    if (!isset($_SESSION['usuario'])) {
        header('Location: login.php');
        exit();
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head> 
         <meta charset="UTF-8">
         <title> ONG Bilbioteca - Gerente </title>
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
                <a href="javascript:void(0)" class="dropbtn"> Clientes </a>
                <div class="dropdown-content">
                    <a href="subtelas/cadastro_cliente.php"> Registrar Cliente </a>
                    <a href="subtelas/ficha_crianca.php"> Consultar Crianças </a>
                    <a href="subtelas/consultar_responsavel.php"> Consultar Responsáveis </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Livros </a>
                <div class="dropdown-content">
                    <a href="subtelas/registrar_livro.php"> Registrar Livro </a>
                    <a href="subtelas/controleestoque.php"> Consultar Livros </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Empréstimos </a>
                <div class="dropdown-content">
                    <a href="subtelas/registrar_emprestimo.php"> Registrar Empréstimo </a>
                    <a href="#"> Consultar Empréstimos </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Doador </a>
                <div class="dropdown-content">
                    <a href="#"> Registrar Doador </a>
                    <a href="#"> Consultar Doadores </a>
                </div>
            </li>
        </ul>
    </body>
    </html>
