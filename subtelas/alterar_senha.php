<?php
    session_start();
    require_once 'conexao.php';

    // GARANTE QUE O USUÁRIO ESTEJA LOGADO
    if (!isset($_SESSION['cod_funcionario'])) {
        echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cod_funcionario = $_SESSION['cod_funcionario'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // VERIFICA SE AS SENHAS COINCIDEM
        if ($nova_senha !== $confirmar_senha) {
            echo "<script>alert('As senhas não coincidem!');</script>";
        } elseif (strlen($nova_senha) < 8) {
            echo "<script>alert('A senha deve ter pelo menos 8 caractéres!');</script>";
        } elseif ($nova_senha === "temp123") {
            echo "<script>alert('Escolha uma senha diferente de temporária!');</script>";
        } else {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

            // ATUALIZA A SENHA E REMOVE O STATUS DE TEMPORÁRIA
            $sql = "UPDATE Funcionario SET Senha = :senha, Senha_Temporaria = FALSE WHERE Cod_Funcionario = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':id', $cod_funcionario);
            $stmt->execute();

            if ($stmt->execute()) {
                session_destroy();
                echo "<script>alert('Senha alterada com sucesso! Faça login novamente.');window.location.href='../index.php';</script>";
            } else {
                echo "<script>alert('Erro ao alterar a senha!');</script>";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recuperar Senha </title>
    <link rel="stylesheet" href="subtelas_css/recuperarsenha.css">
</head>
<body>
    <h2> Alterar Senha </h2>

    <p> Olá, <strong><?php echo $_SESSION['usuario']?></strong> Digite sua nova senha abaixo: </p>

    <div id="password-reset-container" class="password-reset-container">
        <h2 class="password-reset-title">Redefinir Senha</h2>
        <form id="password-reset-form" onsubmit="return resetPassword(event)">
            <div class="password-input-group">
                <label for="new-password">Nova Senha:</label>
                <input type="password" name="nova_senha" id="new-password" class="password-input" required minlength="6">
            </div>
            <div class="password-input-group">
                <label for="confirm-password">Confirmar Nova Senha:</label>
                <input type="password" name="confirmar_senha" id="confirm-password" class="password-input" required minlength="6">
            </div>
            <div class="show-password-container">
                <input type="checkbox" id="show-password" onchange="togglePasswords()">
                <label for="show-password">Mostrar senha</label>
            </div>
            <button type="submit" class="btn-reset">Redefinir</button>
        </form>
    </div>

    <script>
        function mostrarSenha() {
            var senha1 = document.getElementById("nova_senha");
            var senha2 = document.getElementById("confirmar_senha");
            var tipo = senha1.type === "password" ? "text" : "password";
            senha1.type = tipo;
            senha2.type = tipo;
        }
    </script>
</body>
</html>