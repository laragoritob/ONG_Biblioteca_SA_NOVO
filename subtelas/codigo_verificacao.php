<?php
    session_start();
    require_once '../conexao.php';

    try {
        // Supondo que o código digitado venha via POST como um array
        $codigo = $_POST['codigo'] ?? '';

        // Junta os inputs do usuário (caso venha separado em inputs individuais)
        if (is_array($codigo)) {
            $codigo = implode('', $codigo); // transforma array em string '12345'
        }

        // Consulta SQL usando prepared statement PDO
        $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE senha_temporaria = :codigo LIMIT 1");
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Código válido → redireciona para a tela de redefinição
            header("Location: alterar_senha.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Erro ao consultar o código: " . $e->getMessage();
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Recuperar Senha </title>
    <link rel="stylesheet" href="subtelas_css/codigo_verificacao.css">
</head>
<body>
    <br><br><br><br>
    <header>
        <form action="recuperar_senha.php" method="POST">
            <button class="btn-voltar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                    Voltar
                </button>
        </form>
        <h1> Alterar Senha </h1>
    </header>

    <main class="main-content">
        <div class="container">
            <form class="formulario" id="form_pessoal" action="codigo_verificacao.php" method="post" onsubmit="return validaFormulario()">
                <p style="text-align: center;">Enviamos um código de 5 dígitos para seu e-mail!</p>
                <br>
                <div class="verification-inputs">
                    <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                </div>
                <button class="btn" onclick="verifyCode()">Verificar</button>
            </form>
        </div>
    </main>
</body>
</html>