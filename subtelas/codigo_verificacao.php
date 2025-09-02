<?php
session_start();
require_once '../conexao.php';

$codigo_valido = false;
$mensagem_erro = '';

try {
    // Verifica se o formulário foi submetido
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_completo'])) {
        $codigo = $_POST['codigo_completo'] ?? '';
        
        // Consulta SQL usando prepared statement PDO
        $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE senha_temporaria = :codigo LIMIT 1");
        $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        $stmt->execute();
        
        // Verifica se encontrou um registro com este código
        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['codigo_verificacao'] = $codigo;
            $_SESSION['Cod_Funcionario'] = $usuario['Cod_Funcionario'];
            $_SESSION['Usuario'] = $usuario['Usuario'];
            
            header('Location: alterar_senha.php');
            exit();
        } else {
            $mensagem_erro = "Código inválido. Por favor, tente novamente.";
        }
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao consultar o código: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="subtelas_css/codigo_verificacao.css">
    <style>
        .error-message {
            color: #ff3860;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
            display: <?php echo !empty($mensagem_erro) ? 'block' : 'none'; ?>;
        }
        .verification-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .verification-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s;
        }
        .verification-input:focus {
            border-color: #3273dc;
            outline: none;
        }
        .hidden-input {
            position: absolute;
            opacity: 0;
            height: 0;
            width: 0;
        }
        .btn {
            background-color: #3273dc;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2765c8;
        }
        .btn-voltar {
            background: none;
            border: none;
            color: #3273dc;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <br><br><br><br>
    <header>
        <form action="recuperar_senha.php" method="POST">
            <button type="button" class="btn-voltar" onclick="window.history.back()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Voltar
            </button>
        </form>
        <h1>Alterar Senha</h1>
    </header>

    <main class="main-content">
        <div class="container">
            <form class="formulario" id="form_pessoal" method="post" onsubmit="return validaFormulario()">
                <p style="text-align: center;">Enviamos um código de 5 dígitos para seu e-mail!</p>
                <br>
                
                <!-- Campo oculto para receber o código completo via paste -->
                <input type="text" class="hidden-input" id="codigoCompleto" name="codigo_completo" maxlength="5">
                
                <div class="verification-inputs">
                    <input type="text" class="verification-input" id="digit1" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="moveToNext(this, 2)">
                    <input type="text" class="verification-input" id="digit2" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="moveToNext(this, 3)">
                    <input type="text" class="verification-input" id="digit3" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="moveToNext(this, 4)">
                    <input type="text" class="verification-input" id="digit4" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="moveToNext(this, 5)">
                    <input type="text" class="verification-input" id="digit5" maxlength="1" pattern="[0-9]" inputmode="numeric" oninput="moveToNext(this, 6)">
                </div>
                
                <div class="error-message" id="errorMessage"><?php echo $mensagem_erro; ?></div>
                
                <button type="submit" class="btn">Verificar</button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configurar os inputs para permitir colar
            const inputs = document.querySelectorAll('.verification-input');
            const hiddenInput = document.getElementById('codigoCompleto');
            
            // Adicionar evento de colagem para todos os inputs
            inputs.forEach(input => {
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    
                    // Obter texto colado
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    
                    // Preencher os campos com o texto colado
                    if (/^\d{5}$/.test(pastedText)) {
                        for (let i = 0; i < 5; i++) {
                            inputs[i].value = pastedText[i] || '';
                        }
                        
                        // Atualizar o campo oculto
                        hiddenInput.value = pastedText;
                        
                        // Mover foco para o último campo
                        inputs[4].focus();
                    }
                });
                
                input.addEventListener('input', function() {
                    updateHiddenInput();
                });
                
                input.addEventListener('keydown', function(e) {
                    // Permitir navegação com setas e delete
                    if (e.key === 'ArrowLeft' && this.previousElementSibling) {
                        this.previousElementSibling.focus();
                    } else if (e.key === 'ArrowRight' && this.nextElementSibling) {
                        this.nextElementSibling.focus();
                    } else if (e.key === 'Backspace' && this.value === '' && this.previousElementSibling) {
                        this.previousElementSibling.focus();
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
            
            // Inicializar o campo oculto
            updateHiddenInput();
        });
        
        function moveToNext(current, next) {
            if (current.value.length === 1) {
                if (next <= 5) {
                    document.getElementById('digit' + next).focus();
                }
            }
        }
        
        function validaFormulario() {
            const code = document.getElementById('codigoCompleto').value;
            if (code.length !== 5) {
                document.getElementById('errorMessage').textContent = 'Por favor, preencha todos os campos.';
                document.getElementById('errorMessage').style.display = 'block';
                return false;
            }
            return true;
        }
    </script>
</body>
</html>