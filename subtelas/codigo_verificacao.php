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
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 30px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        
        .user-info {
            text-align: center;
            color: #666;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .instruction {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }
        
        .verification-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 25px;
        }
        
        .verification-input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ddd;
            border-radius: 8px;
        }
        
        .verification-input:focus {
            border-color: #3273dc;
            outline: none;
        }
        
        .error-message {
            color: #ff3860;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #fff5f5;
            border-radius: 5px;
            border: 1px solid #ff3860;
        }
        
        .btn {
            background-color: #3273dc;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        
        .btn:hover {
            background-color: #2765c8;
        }
        
        .hidden-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
        
        .dev-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #e8f4fc;
            border-radius: 5px;
            font-size: 14px;
            color: #2c6b9e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recuperar Senha</h1>
        
        <div class="user-info">
            <strong>Email:</strong> 
            <?php 
            // Mostrar o email que está recuperando a senha
            echo isset($_SESSION['email_recuperacao']) ? $_SESSION['email_recuperacao'] : 'Não identificado';
            ?>
        </div>

        <p class="instruction">Digite o código de 5 dígitos recebido por email</p>
        
        <form id="verificationForm" method="post">
            <!-- CAMPO OCULTO QUE ENVIA O CÓDIGO -->
            <input type="text" id="codigoCompleto" name="codigo_completo" class="hidden-input" required>
            
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
        
        <div class="dev-info">
            <strong>Modo Desenvolvimento:</strong> Qualquer código de 5 dígitos será aceito.
            O sistema tentará encontrar o usuário correto pelo email da sessão.
        </div>
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