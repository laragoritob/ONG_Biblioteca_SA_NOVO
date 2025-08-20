<!DOCTYPE html>
<html lang="pt-br">
    <head> 
         <meta charset="UTF-8">
         <title> ONG Bilbioteca - Recreador </title>
         <link rel ="stylesheet" type="text/css" href="css/style.css" />
         <script src="javascript/JS_Logout.js" defer></script>
    </head>
    <body> 
        <header> 
            <h1> Bem-Vindo, "Recreador"! </h1>
            <a href="#" class="logout-btn">🚶🏻‍♂️ Logout</a>
            <img src="img/logo_trans.png" title="imgs" class="logo"> 
        </header>
        <ul class="nav-bar">
            <li><a href="#" class="dropbtn"> Início </a></li>

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
                    <a href="subtelas/controleestoque.php"> Consultar Livros </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Empréstimos </a>
                <div class="dropdown-content">
                    <a href="#"> Consultar Empréstimos </a>
                </div>
            </li>
        </ul>
    </body>
    </html>
