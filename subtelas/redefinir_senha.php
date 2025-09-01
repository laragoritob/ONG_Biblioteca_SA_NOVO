<?php
    session_start();
    require_once '../conexao.php';

    $token = isset($_GET['token']) ? trim($_GET['token']) : '';
    $error = '';
    $success = '';
    $showForm = false;
    $codFuncionario = null;

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($token === '') {
            $error = 'Token inválido.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT Cod_Funcionario, expires_at FROM password_resets WHERE token = :token ORDER BY id DESC LIMIT 1");
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $expiresAt = new DateTime($row['expires_at']);
                    $now = new DateTime('now');
                    if ($now <= $expiresAt) {
                        $showForm = true;
                        $codFuncionario = $row['Cod_Funcionario'];
                    } else {
                        $error = 'Token expirado. Solicite uma nova redefinição.';
                    }
                } else {
                    $error = 'Token inválido.';
                }
            } catch (Exception $e) {
                $error = 'Erro ao validar o token.';
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = isset($_POST['token']) ? trim($_POST['token']) : '';
        $novaSenha = isset($_POST['nova_senha']) ? trim($_POST['nova_senha']) : '';
        $confirmacao = isset($_POST['confirmacao_senha']) ? trim($_POST['confirmacao_senha']) : '';

        if ($token === '') {
            $error = 'Token inválido.';
        } elseif ($novaSenha === '' || $confirmacao === '') {
            $error = 'Preencha a nova senha e a confirmação.';
        } elseif ($novaSenha !== $confirmacao) {
            $error = 'As senhas não coincidem.';
        } elseif (strlen($novaSenha) < 6) {
            $error = 'A senha deve ter pelo menos 6 caracteres.';
        } else {
            try {
                $stmt = $pdo->prepare("SELECT id, Cod_Funcionario, expires_at FROM password_resets WHERE token = :token ORDER BY id DESC LIMIT 1");
                $stmt->bindParam(':token', $token);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$row) {
                    $error = 'Token inválido.';
                } else {
                    $expiresAt = new DateTime($row['expires_at']);
                    if (new DateTime('now') > $expiresAt) {
                        $error = 'Token expirado. Solicite uma nova redefinição.';
                    } else {
                        // Atualiza a senha do funcionário (armazenada como texto simples para compatibilidade com o esquema atual)
                        $upd = $pdo->prepare("UPDATE funcionario SET Senha = :senha WHERE Cod_Funcionario = :cid");
                        $upd->bindParam(':senha', $novaSenha);
                        $upd->bindParam(':cid', $row['Cod_Funcionario']);
                        $upd->execute();

                        // Remove o token usado
                        $del = $pdo->prepare("DELETE FROM password_resets WHERE id = :id");
                        $del->bindParam(':id', $row['id']);
                        $del->execute();

                        echo "<script>alert('Senha redefinida com sucesso!');window.location.href='../index.php';</script>";
                        exit();
                    }
                }
            } catch (Exception $e) {
                $error = 'Erro ao redefinir senha.';
            }
        }
        // Se houve erro, mostra formulário novamente
        if ($error !== '') {
            $showForm = true;
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/recuperarsenha.css">
    <style>
        .error { color: #b00020; margin-top: 8px; }
        .success { color: #0a7a0a; margin-top: 8px; }
    </style>
    </head>
<body>
    <div class="container">
        <div class="logodiv">
            <img src="../img/logo_ong.png" title="Logo da Biblioteca" class="logo">
        </div>
        <div class="conteudo-direito">
            <h1>REDEFINIR SENHA</h1>
            <?php if ($error !== ''): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
                <div class="links" style="margin-top: 12px;">
                    <a href="recuperar_senha.php">Solicitar novo link</a>
                </div>
            <?php endif; ?>
            <?php if ($showForm): ?>
                <form class="formulario" method="POST" action="redefinir_senha.php">
                    <div class="input-group">
                        <span class="icon">🔒</span>
                        <input type="password" name="nova_senha" id="nova_senha" placeholder="Nova senha" required>
                    </div>
                    <div class="input-group">
                        <span class="icon">🔒</span>
                        <input type="password" name="confirmacao_senha" id="confirmacao_senha" placeholder="Confirme a nova senha" required>
                    </div>
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="links">
                        <button type="submit" class="btn">Salvar nova senha</button>
                    </div>
                </form>
                <a href="../index.php">← Voltar</a>
            <?php endif; ?>
        </div>
    </div>
</body>
<footer>
    <p>Copyright © 2024 - ONG Biblioteca - Sala Arco-íris</p>
</footer>
</html>

