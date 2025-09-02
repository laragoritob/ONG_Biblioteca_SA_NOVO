<?php
session_start();
require_once '../conexao.php';

// Verificar se o usuário veio da verificação do código
if (!isset($_SESSION['codigo_verificacao']) || !isset($_SESSION['Cod_Funcionario'])) {
    header('Location: recuperar_senha.php');
    exit();
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cod_funcionario = $_SESSION['Cod_Funcionario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // VERIFICA SE AS SENHAS COINCIDEM
    if ($nova_senha !== $confirmar_senha) {
        $mensagem = "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha) < 8) {
        $mensagem = "<script>alert('A senha deve ter pelo menos 8 caracteres!');</script>";
    } elseif ($nova_senha === "temp123") {
        $mensagem = "<script>alert('Escolha uma senha diferente da temporária!');</script>";
    } else {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

        // ATUALIZA A SENHA E REMOVE O STATUS DE TEMPORÁRIA
        $sql = "UPDATE funcionario 
                SET Senha = :senha, Senha_Temporaria = NULL 
                WHERE Cod_Funcionario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':id', $cod_funcionario);

        if ($stmt->execute()) {
            // Limpar sessão e redirecionar
            session_unset();
            session_destroy();
            echo "<script>alert('Senha alterada com sucesso! Faça login novamente.');window.location.href='../index.php';</script>";
            exit();
        } else {
            $mensagem = "<script>alert('Erro ao alterar a senha!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="subtelas_css/recuperarsenha.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        
        .password-reset-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .password-reset-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        
        .password-input-group {
            margin-bottom: 20px;
        }
        
        .password-input-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .password-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .show-password-container {
            margin-bottom: 20px;
        }
        
        .btn-reset {
            background-color: #3273dc;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        
        .btn-reset:hover {
            background-color: #2765c8;
        }
    </style>
</head>
<body>
    <h2>Alterar Senha</h2>

    <p>Olá, <strong><?php echo $_SESSION['Usuario'] ?? ''; ?></strong>. Digite sua nova senha abaixo:</p>

    <div id="password-reset-container" class="password-reset-container">
        <h2 class="password-reset-title">Redefinir Senha</h2>
        <?php echo $mensagem; ?>
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