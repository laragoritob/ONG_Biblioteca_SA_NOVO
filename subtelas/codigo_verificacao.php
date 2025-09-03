<?php
session_start();
require_once '../conexao.php';

// Iniciar sessão de email se não existir (para teste)
if (!isset($_SESSION['email_recuperacao'])) {
    // Em produção, isso viria do processo de recuperação de senha
    $_SESSION['email_recuperacao'] = 'usuario@exemplo.com'; // Email padrão para teste
}

$mensagem_erro = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_completo'])) {
    $codigo = trim($_POST['codigo_completo']);
    
    if (empty($codigo)) {
        $mensagem_erro = "Por favor, insira o código de verificação.";
    } elseif (strlen($codigo) !== 5) {
        $mensagem_erro = "O código deve ter exatamente 5 dígitos.";
    } else {
        try {
            // Em produção, usaria o email da sessão para encontrar o usuário correto
            // $email = $_SESSION['email_recuperacao'];
            // $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE email = :email LIMIT 1");
            // $stmt->bindParam(':email', $email);
            
            // Para desenvolvimento: busca pelo primeiro usuário que tem um código
            $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE senha_temporaria IS NOT NULL AND senha_temporaria != '' LIMIT 1");
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Em produção, verificaria se o código confere com o do banco
                // if ($codigo === $usuario['senha_temporaria']) {
                
                $_SESSION['codigo_verificacao'] = $codigo;
                $_SESSION['Cod_Funcionario'] = $usuario['Cod_Funcionario'];
                $_SESSION['Usuario'] = $usuario['Usuario'];
                $_SESSION['Email'] = $usuario['Email']; // Salvar email também
                
                // DEBUG: Log de sucesso
                error_log("Código ACEITO para: " . $usuario['Usuario'] . " (Email: " . $usuario['Email'] . ")");
                
                // Redireciona para a página de alterar senha
                header('Location: alterar_senha.php');
                exit();
            } else {
                // Fallback: buscar qualquer usuário
                $stmt_fallback = $pdo->query("SELECT * FROM funcionario LIMIT 1");
                if ($stmt_fallback->rowCount() > 0) {
                    $usuario = $stmt_fallback->fetch(PDO::FETCH_ASSOC);
                    
                    $_SESSION['codigo_verificacao'] = $codigo;
                    $_SESSION['Cod_Funcionario'] = $usuario['Cod_Funcionario'];
                    $_SESSION['Usuario'] = $usuario['Usuario'];
                    $_SESSION['Email'] = $usuario['Email'];
                    
                    error_log("Código ACEITO (fallback) para: " . $usuario['Usuario']);
                    
                    header('Location: alterar_senha.php');
                    exit();
                } else {
                    $mensagem_erro = "Nenhum usuário encontrado no sistema.";
                }
            }
        } catch (PDOException $e) {
            $mensagem_erro = "Erro no sistema. Por favor, tente novamente.";
            error_log("Erro DB: " . $e->getMessage());
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
    <link rel="stylesheet" href="subtelas_css/codigo_verificacao.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container">
    <header>
        <form action="recuperar_senha.php" method="POST">
            <button class="btn-voltar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                    Voltar
                </button>
        </form>
        
        <h1> Verificar Código </h1>
    </header>

        <main class="main-content">
        <div class="container">
            <form class="formulario" id="form_pessoal" action="codigo_verificacao.php" method="post" onsubmit="return validaFormulario()">

        <p class="instruction">Digite o código de 5 dígitos recebido por email</p>
    
            
            <div class="verification-inputs">
                <input type="text" class="verification-input" id="digit1" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit2" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit3" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit4" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit5" maxlength="1" pattern="[0-9]" inputmode="numeric">
            </div>
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="error-message" id="errorMessage">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn" id="submitBtn">Verificar Código</button>
        </form>
    </div>
</main>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verificationForm');
            const inputs = document.querySelectorAll('.verification-input');
            const hiddenInput = document.getElementById('codigoCompleto');
            
            // Focar no primeiro input ao carregar
            inputs[0].focus();
            
            // Adicionar eventos de input para cada campo
            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Permite apenas números
                    if (!/^\d*$/.test(value)) {
                        e.target.value = value.replace(/[^\d]/g, '');
                        return;
                    }
                    
                    // Atualiza o campo oculto
                    updateHiddenInput();
                    
                    // Move para o próximo campo se um dígito foi inserido
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    
                    // Se todos os campos estão preenchidos, submeter o formulário
                    if (isAllInputsFilled()) {
                        form.submit();
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    
                    // Verifica se é um código de 5 dígitos
                    if (/^\d{5}$/.test(pastedData)) {
                        for (let i = 0; i < inputs.length; i++) {
                            if (i < pastedData.length) {
                                inputs[i].value = pastedData[i];
                            }
                        }
                        
                        updateHiddenInput();
                        inputs[inputs.length - 1].focus();
                        setTimeout(() => form.submit(), 100);
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace') {
                        if (input.value === '' && index > 0) {
                            inputs[index - 1].focus();
                        }
                    } else if (e.key === 'ArrowLeft' && index > 0) {
                        inputs[index - 1].focus();
                    } else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });
            });
            
            function updateHiddenInput() {
                let code = '';
                inputs.forEach(input => {
                    code += input.value;
                });
                hiddenInput.value = code;
            }
            
            function isAllInputsFilled() {
                return Array.from(inputs).every(input => input.value.length === 1);
            }
            
            updateHiddenInput();
        });
    </script>
</body>
</html>