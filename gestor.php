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
         <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/relatorios.css" />
        <script src="javascript/JS_Logout.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
                    <a href="subtelas/consultar_funcionario.php"> Consultar Funcionários </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Doador </a>
                <div class="dropdown-content">
                    <a href="subtelas/registrar_doador.php"> Registrar Doador </a>
                    <a href="subtelas/consultar_doador.php"> Consultar Doadores </a>
                </div>
            </li>
        </ul>
    </body>
    </html>
