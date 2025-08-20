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
            <h1> Bem-Vindo, "Gestor"! </h1>
            <a href="#" class="logout-btn">🚶🏻‍♂️ Logout</a>
            <img src="img/logo_trans.png" title="imgs" class="logo"> 
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
                    <a href="subtelas/controleestoque.php"> Consultar Livros </a>
                </div>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Doador </a>
                <div class="dropdown-content">
                    <a href="#"> Consultar Doadores </a>
                </div>
            </li>
        </ul>
    </body>
    </html>
