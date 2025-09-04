<?php
// Inicia a sessão para verificar autenticação
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário veio da verificação do código de recuperação
// Esta página só pode ser acessada após validação do código enviado por email
if (!isset($_SESSION['codigo_verificacao']) || !isset($_SESSION['Cod_Funcionario'])) {
    header('Location: recuperar_senha.php');
    exit();
}

// Variável para armazenar mensagens de erro
$mensagem = '';

// Executado apenas quando o formulário é enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $cod_funcionario = $_SESSION['Cod_Funcionario'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verifica se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        $mensagem = "<script>alert('As senhas não coincidem!');</script>";
    } elseif (strlen($nova_senha) < 8) {
        // Valida se a senha tem pelo menos 8 caracteres
        $mensagem = "<script>alert('A senha deve ter pelo menos 8 caracteres!');</script>";
    } elseif ($nova_senha === "temp123") {
        // Impede o uso da senha temporária padrão
        $mensagem = "<script>alert('Escolha uma senha diferente da temporária!');</script>";
    } else {
        // Se passou por todas as validações, atualiza a senha no banco
        $sql = "UPDATE funcionario 
                SET Senha = :senha, Senha_Temporaria = NULL 
                WHERE Cod_Funcionario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $nova_senha);
        $stmt->bindParam(':id', $cod_funcionario);

        if ($stmt->execute()) {
            // Se sucesso, limpa a sessão e redireciona para login
            session_unset();
            session_destroy();
            echo "<script>alert('Senha alterada com sucesso! Faça login novamente.');window.location.href='../index.php';</script>";
            exit();
        } else {
            // Se falhou, exibe mensagem de erro
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
    <link rel="stylesheet" type="text/css" href="subtelas_css/alterar_senha.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="header-container">
        <h2>Alterar Senha</h2>
        <p>Olá, <strong><?php echo $_SESSION['Usuario'] ?? ''; ?></strong>. Digite sua nova senha abaixo:</p>
    </div>

    <div id="password-reset-container" class="password-reset-container">
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
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>