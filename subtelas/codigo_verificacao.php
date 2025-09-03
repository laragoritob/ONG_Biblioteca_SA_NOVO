<?php
session_start();
require_once '../conexao.php';

$mensagem_erro = '';

// Verifica se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_completo'])) {
    $codigo = trim($_POST['codigo_completo']);
    
    // DEBUG: Log do código recebido
    error_log("Código recebido: " . $codigo);
    
    if (empty($codigo)) {
        $mensagem_erro = "Por favor, insira o código de verificação.";
    } elseif (strlen($codigo) !== 5) {
        $mensagem_erro = "O código deve ter exatamente 5 dígitos.";
    } else {
        try {
            // Função para verificar se o código foi solicitado e retornar o email correspondente
            function obterEmailDoCodigoSolicitado($codigo) {
                $arquivo = 'emails_simulados.txt';
                if (!file_exists($arquivo)) {
                    return false;
                }
                
                $conteudo = file_get_contents($arquivo);
                $linhas = explode("\n", $conteudo);
                
                for ($i = 0; $i < count($linhas); $i++) {
                    $linha = trim($linhas[$i]);
                    
                    // Procura por linha que contém o código
                    if (strpos($linha, "Olá! Seu código de recuperação é: " . $codigo) !== false) {
                        // Volta uma linha para pegar o email
                        if ($i > 0) {
                            $linha_email = trim($linhas[$i - 1]);
                            if (strpos($linha_email, "Para: ") !== false) {
                                $email = str_replace("Para: ", "", $linha_email);
                                return $email;
                            }
                        }
                    }
                }
                return false;
            }
            
            // Verifica se o código foi solicitado e obtém o email correspondente
            $email_do_codigo = obterEmailDoCodigoSolicitado($codigo);
            
            if ($email_do_codigo) {
                // Busca o usuário pelo email que solicitou o código
                $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE Email = :email");
                $stmt->bindParam(':email', $email_do_codigo);
                $stmt->execute();
                
                if ($stmt->rowCount() > 0) {
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    $_SESSION['codigo_verificacao'] = $codigo;
                    $_SESSION['Cod_Funcionario'] = $usuario['Cod_Funcionario'];
                    $_SESSION['Usuario'] = $usuario['Usuario'];
                    $_SESSION['Email'] = $usuario['Email'];
                    
                    // DEBUG: Log de sucesso
                    error_log("Código SOLICITADO VÁLIDO: " . $codigo . " para: " . $usuario['Usuario'] . " (Email: " . $usuario['Email'] . ")");
                    
                    // Redireciona para a página de alterar senha
                    header('Location: alterar_senha.php');
                    exit();
                } else {
                    $mensagem_erro = "Usuário não encontrado no sistema para o email: " . $email_do_codigo;
                    error_log("Usuário não encontrado para email: " . $email_do_codigo);
                }
            } else {
                $mensagem_erro = "Código inválido. Este código não foi solicitado.";
                error_log("Código NÃO SOLICITADO: " . $codigo);
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
            <form class="formulario" id="verificationForm" action="codigo_verificacao.php" method="post">

        <p class="instruction">Digite o código de 5 dígitos recebido por email</p>
            
            <div class="verification-inputs">
                <input type="text" class="verification-input" id="digit1" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit2" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit3" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit4" maxlength="1" pattern="[0-9]" inputmode="numeric">
                <input type="text" class="verification-input" id="digit5" maxlength="1" pattern="[0-9]" inputmode="numeric">
            </div>
            
            <!-- Campo oculto para enviar o código completo -->
            <input type="hidden" name="codigo_completo" id="codigoCompleto" value="">
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="error-message" id="errorMessage">
                    <?php echo $mensagem_erro; ?>
                </div>
            <?php endif; ?>
            
            <button type="submit" class="btn" id="submitBtn" onclick="return validateAndSubmit()">Verificar Código</button>
        </form>
    </div>
</main>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('verificationForm');
            const inputs = document.querySelectorAll('.verification-input');
            const hiddenInput = document.getElementById('codigoCompleto');
            
            // Verificar se os elementos existem
            if (!form || !inputs.length || !hiddenInput) {
                console.error('Elementos do formulário não encontrados');
                return;
            }
            
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
                        setTimeout(() => form.submit(), 500); // Pequeno delay para garantir que o campo oculto seja atualizado
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').trim();
                    
                    // Verifica se é um código de 5 dígitos
                    if (/^\d{5}$/.test(pastedData)) {
                        // Limpa todos os campos primeiro
                        inputs.forEach(input => input.value = '');
                        
                        // Preenche os campos com o código colado
                        for (let i = 0; i < inputs.length && i < pastedData.length; i++) {
                            inputs[i].value = pastedData[i];
                        }
                        
                        updateHiddenInput();
                        inputs[inputs.length - 1].focus();
                        
                        // Feedback visual
                        inputs.forEach(input => {
                            input.style.backgroundColor = '#e8f5e8';
                            setTimeout(() => {
                                input.style.backgroundColor = '';
                            }, 1000);
                        });
                        
                        setTimeout(() => form.submit(), 800);
                    } else {
                        // Feedback para código inválido
                        const currentInput = e.target;
                        currentInput.style.backgroundColor = '#ffe6e6';
                        setTimeout(() => {
                            currentInput.style.backgroundColor = '';
                        }, 1000);
                        
                        // Mostra mensagem de erro temporária
                        showTemporaryMessage('Código inválido. Digite exatamente 5 dígitos.', 'error');
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
            
            // Atualizar campo oculto inicialmente
            updateHiddenInput();
            
            // Evento de paste global no formulário
            form.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').trim();
                
                // Verifica se é um código de 5 dígitos
                if (/^\d{5}$/.test(pastedData)) {
                    // Limpa todos os campos primeiro
                    inputs.forEach(input => input.value = '');
                    
                    // Preenche os campos com o código colado
                    for (let i = 0; i < inputs.length && i < pastedData.length; i++) {
                        inputs[i].value = pastedData[i];
                    }
                    
                    updateHiddenInput();
                    inputs[0].focus();
                    
                    // Feedback visual
                    inputs.forEach(input => {
                        input.style.backgroundColor = '#e8f5e8';
                        setTimeout(() => {
                            input.style.backgroundColor = '';
                        }, 1000);
                    });
                    
                    setTimeout(() => form.submit(), 800);
                } else {
                    // Mostra mensagem de erro temporária
                    showTemporaryMessage('Código inválido. Digite exatamente 5 dígitos.', 'error');
                }
            });
        });
        
        // Função para validar e submeter o formulário
        function validateAndSubmit() {
            const inputs = document.querySelectorAll('.verification-input');
            const hiddenInput = document.getElementById('codigoCompleto');
            
            // Verificar se todos os campos estão preenchidos
            let code = '';
            for (let i = 0; i < inputs.length; i++) {
                if (!inputs[i].value || inputs[i].value.length !== 1) {
                    alert('Por favor, preencha todos os 5 dígitos do código.');
                    inputs[i].focus();
                    return false;
                }
                code += inputs[i].value;
            }
            
            // Atualizar o campo oculto
            hiddenInput.value = code;
            
            // Verificar se o código tem 5 dígitos
            if (code.length !== 5) {
                alert('O código deve ter exatamente 5 dígitos.');
                return false;
            }
            
            return true;
        }
        
        // Função para mostrar mensagens temporárias
        function showTemporaryMessage(message, type) {
            // Remove mensagem anterior se existir
            const existingMessage = document.getElementById('temp-message');
            if (existingMessage) {
                existingMessage.remove();
            }
            
            // Cria nova mensagem
            const messageDiv = document.createElement('div');
            messageDiv.id = 'temp-message';
            messageDiv.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                padding: 12px 24px;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                transition: all 0.3s ease;
                ${type === 'error' ? 'background: #f44336;' : 'background: #4caf50;'}
            `;
            messageDiv.textContent = message;
            
            document.body.appendChild(messageDiv);
            
            // Remove a mensagem após 3 segundos
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.style.opacity = '0';
                    messageDiv.style.transform = 'translateX(-50%) translateY(-20px)';
                    setTimeout(() => {
                        if (messageDiv.parentNode) {
                            messageDiv.remove();
                        }
                    }, 300);
                }
            }, 3000);
        }
    </script>
</body>
</html>