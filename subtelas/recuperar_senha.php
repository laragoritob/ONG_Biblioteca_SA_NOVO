<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Inclui o arquivo com as funções que geram a senha e simulam o envio de email
require_once 'funcoes_email.php';

// Verifica se o formulário foi enviado via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém o email do formulário
    $email = $_POST['email'];

    // Consulta SQL para verificar se o email existe no banco de dados
    $sql = "SELECT * FROM funcionario WHERE email = :email AND status = 'ativo'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se encontrou o usuário com o email informado
    if ($usuario) {
        // Gera uma senha temporária e aleatória usando função do arquivo funcoes_email.php
        $senha_temporaria = gerarCodigoRecuperacao();
        // Cria hash da senha temporária para armazenar no banco
        $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

        // Atualiza a senha no banco de dados e marca como temporária
        $sql = "UPDATE funcionario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senha_hash);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Simula o envio do email gravando em arquivo TXT
        simularEnvioEmail($email, $senha_temporaria);
        // Exibe mensagem de sucesso e redireciona para verificação
        echo "<script>alert('Uma senha temporária foi gerada e enviada.');window.location.href='codigo_verificacao.php';</script>";
    } else {
        // Se não encontrou o email, exibe mensagem de erro
        echo "<script>alert('Email não encontrado.');window.location.href='recuperar_senha.php';</script>";
    }
} 
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/recuperarsenha.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
        <div class="logodiv">
            <img src="../img/logo_ong.png" title="Logo da Biblioteca" class="logo">
        </div>

        <div class="conteudo-direito">
            <h1>RECUPERAR SENHA</h1>
            <form class="formulario" id="form_login" action="recuperar_senha.php" method="POST">
                <div class="input-group">
                    <span class="icon">📩</span>
                    <input type="text" name="email" id="email" placeholder="Digite seu email" required>
                </div>

                <div class="links">
                    <button type="submit" class="btn"> Enviar </button>
                </div>
            </form>
            <a href="../index.php"> ← Voltar </a>
        </div>
    </div>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
<footer>
    <p>Copyright © 2024 - ONG Biblioteca - Sala Arco-íris</p>
</footer>
</html>