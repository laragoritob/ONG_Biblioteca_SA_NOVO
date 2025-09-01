<?php
    session_start();
    require_once '../conexao.php';
    require_once 'funcoes_email.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $siteBaseUrl = isset($_POST['site_base_url']) ? rtrim(trim($_POST['site_base_url']), '/') : '';

        if ($email === '') {
            echo "<script>alert('Informe um email válido.');</script>";
        } else {
            $sql = "SELECT Cod_Funcionario, Email, Nome FROM funcionario WHERE Email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Garante a existência da tabela de resets
                $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    Cod_Funcionario INT NOT NULL,
                    token VARCHAR(255) NOT NULL,
                    expires_at DATETIME NOT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    INDEX (token),
                    INDEX (Cod_Funcionario)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

                $token = gerarToken(32);
                $expiresAt = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

                $ins = $pdo->prepare("INSERT INTO password_resets (Cod_Funcionario, token, expires_at) VALUES (:cid, :token, :exp)");
                $ins->bindParam(':cid', $usuario['Cod_Funcionario']);
                $ins->bindParam(':token', $token);
                $ins->bindParam(':exp', $expiresAt);
                $ins->execute();

                if ($siteBaseUrl === '') {
                    // Fallback: tenta construir base a partir do host
                    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
                    $siteBaseUrl = $scheme . '://' . $host;
                }

                $resetLink = $siteBaseUrl . '/subtelas/redefinir_senha.php?token=' . urlencode($token);
                simularEnvioEmailReset($email, $resetLink);
                echo "<script>alert('Enviamos um link de redefinição. Confira o arquivo de emails simulado.');window.location.href='../index.php';</script>";
                exit();
            } else {
                echo "<script>alert('Email não encontrado.');</script>";
            }
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
                <input type="hidden" name="site_base_url" id="site_base_url" value="">
            </form>
            <a href="../index.php"> ← Voltar </a>
        </div>
    </div>
</body>
<footer>
    <p>Copyright © 2024 - ONG Biblioteca - Sala Arco-íris</p>
</footer>
</html>

<script src="subtelas_javascript/recuperar_senha.js"></script>