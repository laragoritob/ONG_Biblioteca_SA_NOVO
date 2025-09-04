<?php
    session_start();
    require_once '../conexao.php';
    require_once 'funcoes_email.php';  // ARQUIVO COM AS FUNÇÕES QUE GERAM A SENHA E SIMULAM O ENVIO

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];

        // VERIFICA SE O EMAIL EXISTE NO BANCO DE DADOS
        $sql = "SELECT * FROM funcionario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // GERA UMA SENHA TEMPORÁRIA E ALEATÓRIA
            $senha_temporaria = gerarCodigoRecuperacao();
            $senha_hash = password_hash($senha_temporaria, PASSWORD_DEFAULT);

            // ATUALIZA A SENHA NO BANCO DE DADOS
            $sql = "UPDATE funcionario SET senha = :senha, senha_temporaria = TRUE WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':senha', $senha_hash);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // SIMULA O ENVIO DO EMAIL (GRAVA EM TXT)
            simularEnvioEmail($email, $senha_temporaria);
            echo "<script>alert('Uma senha temporária foi gerada e enviada.');window.location.href='codigo_verificacao.php';</script>";
        } else {
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