<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Apenas Gerente (perfil 1) pode pagar multas
if ($_SESSION['perfil'] != 1) {
    // Se não tem permissão, define mensagem de erro
    $erro = "Acesso Negado! Apenas gerentes podem pagar multas.";
} else {
// Verifica se o ID da multa foi fornecido via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
        // Se não foi fornecido ID, define mensagem de erro
        $erro = "ID da multa não fornecido!";
    } else {
// Converte o ID da multa para inteiro para segurança (previne SQL injection)
$cod_multa = intval($_GET['id']);

try {
    // Consulta SQL para verificar se a multa existe e se já não está paga
    $sql_check = "SELECT Status_Multa FROM multa WHERE Cod_Multa = :cod_multa";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(":cod_multa", $cod_multa, PDO::PARAM_INT);
    $stmt_check->execute();
    
    $multa = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    // Verifica se encontrou a multa
    if (!$multa) {
                $erro = "Multa não encontrada!";
            } else {
    // Verifica se a multa já foi paga
    if ($multa['Status_Multa'] === 'Paga') {
                    $erro = "Esta multa já foi paga!";
                } else {
    // Atualiza o status da multa para "Paga"
    $sql_update = "UPDATE multa SET Status_Multa = 'Paga' WHERE Cod_Multa = :cod_multa";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(":cod_multa", $cod_multa, PDO::PARAM_INT);
    
    // Executa a atualização e verifica o resultado
    if ($stmt_update->execute()) {
                        // Se sucesso, define mensagem de sucesso
                        $sucesso = "Multa marcada como paga com sucesso!";
    } else {
                        // Se falhou, define mensagem de erro
                        $erro = "Erro ao atualizar o status da multa!";
                    }
                }
    }
    
} catch (PDOException $e) {
            // Em caso de erro na execução, captura e define a mensagem
            $erro = "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Pagar Multa</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .swal2-title-arial {
            font-family: Arial, sans-serif !important;
            font-weight: bold !important;
        }
        
        .swal2-html-arial {
            font-family: Arial, sans-serif !important;
            font-size: 16px !important;
        }
        
        /* Estilo dos botões igual ao cadastro_funcionario */
        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:focus {
            outline: 2px solid #6366f1 !important;
            outline-offset: 2px !important;
        }
        
        .swal2-cancel {
            background: #dc2626 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-cancel:hover {
            background: #b91c1c !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
    </style>
</head>
<body>
    <script>
        // Aguardar o SweetAlert2 carregar
        document.addEventListener('DOMContentLoaded', function() {
            // Mostrar notificações baseadas no PHP
            <?php if (isset($sucesso)): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    html: '<?= addslashes($sucesso) ?>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1',
                    customClass: {
                        title: 'swal2-title-arial',
                        htmlContainer: 'swal2-html-arial',
                        confirmButton: 'swal2-confirm'
                    }
                }).then(() => {
                    window.location.href = 'consultar_multa.php';
                });
            <?php endif; ?>
            
            <?php if (isset($erro)): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    html: '<?= addslashes($erro) ?>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1',
                    customClass: {
                        title: 'swal2-title-arial',
                        htmlContainer: 'swal2-html-arial',
                        confirmButton: 'swal2-confirm'
                    }
                }).then(() => {
                    window.location.href = 'consultar_multa.php';
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
