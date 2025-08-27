<?php
    session_start();
    require_once 'conexao.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM funcionario WHERE usuario = :usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // LOGIN BEM SUCEDIDO DEFINE VARIÁVEIS DE SESSÃO
            $_SESSION['usuario'] = $usuario['Nome'];
            $_SESSION['perfil'] = $usuario['Cod_Perfil'];
            $_SESSION['cod_func'] = $usuario['Cod_usuario'];

            // REDIRECIONA PARA A PÁGINA CORRESPONDENTE AO PERFIL
            switch ($usuario['Cod_Perfil']) {
                case 1: // Gerente
                    header("Location: gerente.php");
                    break;
                case 2: // Gestor
                    header("Location: gestor.php");
                    break;
                case 3: // Bibliotecário
                    header("Location: bibliotecario.php");
                    break;
                case 4: // Recreador
                    header("Location: recreador.php");
                    break;
                case 5: // Repositor
                    header("Location: repositor.php");
                    break;
                default:
                    // PERFIL NÃO RECONHECIDO, REDIRECIONA PARA LOGIN
                    header("Location: index.php");
                    break;
            }
            exit();
        } else {
            // USUÁRIO NÃO ENCONTRADO, REDIRECIONA PARA LOGIN
            header("Location: index.php");
            exit();
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div class="container">
        <div class="logodiv">
            <img src="img/logo_ong.png" title="Logo da Biblioteca" class="logo">
        </div>

        <div class="conteudo-direito">
            <h1>LOGIN</h1>
            <form class="formulario" id="form_login" action="index.php" method="POST">
                <div class="input-group">
                    <span class="icon">👤</span>
                    <input type="text" name="usuario" id="usuario" placeholder="Usuário" required>
                </div>

                <div class="input-group">
                    <span class="icon">🔒</span>
                    <input type="password" name="senha" id="senha" placeholder="Senha" required>
                </div>
                <div class="links">
                    <button type="submit" class="btn"> Acessar </button>
                </div>
            </form>
            <a href="subtelas/recuperar_senha.html"> Esqueceu sua senha? </a>
        </div>
    </div>
</body>
<footer>
    <p>Copyright © 2024 - ONG Biblioteca - Sala Arco-íris</p>
</footer>
</html>
