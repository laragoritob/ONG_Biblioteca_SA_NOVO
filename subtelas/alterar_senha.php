<?php
session_start();
require_once '../conexao.php';

    //GARANTE QUE O USUÁRIO ESTEJA LOGADO
    if (!isset($_SESSION['funcionario'])) {
       header('Location: index.php');
        exit();
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cod_funcionario = $_SESSION['Cod_Funcionario'];
    $nova_senha = $_POST['nova_senha'];          // corrigido para minúsculo
    $confirmar_senha = $_POST['confirmar_senha']; // corrigido para minúsculo

    // VERIFICA SE AS SENHAS COINCIDEM
    if ($nova_senha !== $confirmar_senha) {
        echo "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha) < 8) {
        echo "<script>alert('A senha deve ter pelo menos 8 caracteres!');</script>";
    } elseif ($nova_senha === "temp123") {
        echo "<script>alert('Escolha uma senha diferente da temporária!');</script>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // ATUALIZA A SENHA E REMOVE O STATUS DE TEMPORÁRIA
        $sql = "UPDATE funcionario 
                SET Senha = :senha, Senha_Temporaria = FALSE 
                WHERE Cod_Funcionario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $cod_funcionario);

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
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="subtelas_css/recuperarsenha.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h2>Alterar Senha</h2>

    <p>Olá, <strong><?php echo $_SESSION['Usuario'] ?></strong>. Digite sua nova senha abaixo:</p>

    <div id="password-reset-container" class="password-reset-container">
        <h2 class="password-reset-title">Redefinir Senha</h2>
        <form id="password-reset-form" action="alterar_senha.php" method="POST">
            <div class="password-input-group">
                <label for="new-password">Nova Senha:</label>
                <input type="password" name="nova_senha" id="new-password" class="password-input" required minlength="8">
            </div>
            <div class="password-input-group">
                <label for="confirm-password">Confirmar Nova Senha:</label>
                <input type="password" name="confirmar_senha" id="confirm-password" class="password-input" required minlength="8">
            </div>
            <div class="show-password-container">
                <input type="checkbox" id="show-password" onclick="togglePasswords()">
                <label for="show-password">Mostrar senha</label>
            </div>
            <button type="submit" class="btn-reset">Redefinir</button>
        </form>
    </div>

    <script>
        function togglePasswords() {
            const senha1 = document.getElementById("new-password");
            const senha2 = document.getElementById("confirm-password");
            const tipo = senha1.type === "password" ? "text" : "password";
            senha1.type = tipo;
            senha2.type = tipo;
        }
    </script>
</body>
</html>
