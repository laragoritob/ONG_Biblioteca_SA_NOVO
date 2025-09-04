<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se é uma confirmação de exclusão ou apenas exibição da confirmação
$confirmado = isset($_GET['confirmado']) && $_GET['confirmado'] === 'true';

// Verifica se foi fornecido um ID via GET
if (isset($_GET['id'])) {
    // Converte o ID para inteiro para segurança (previne SQL injection)
    $id = intval($_GET['id']);

    // Se a exclusão foi confirmada, procede com a exclusão
    if ($confirmado) {
        try {
            // Primeiro verifica se o doador existe antes de excluir
            $sql_check = "SELECT Nome_Doador FROM doador WHERE Cod_Doador = :id";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            
            $doador = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            // Verifica se encontrou o doador
            if (!$doador) {
                $erro = "Doador não encontrado!";
            } else {
                // Se encontrou, procede com a exclusão
                $sql = "DELETE FROM doador WHERE Cod_Doador = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                // Executa a exclusão e verifica o resultado
                if ($stmt->execute()) {
                    // Se sucesso, define mensagem de sucesso com o nome do doador
                    $sucesso = "Doador " . htmlspecialchars($doador['Nome_Doador']) . " excluído com sucesso!";
                } else {
                    // Se falhou, define mensagem de erro
                    $erro = "Erro ao excluir doador.";
                }
            }
        } catch (PDOException $e) {
            // Em caso de erro na execução, captura e exibe a mensagem
            $erro = "Erro ao excluir doador: " . $e->getMessage();
        }
    }
    // Se não for confirmado, apenas mostra a tela de confirmação
} else {
    // Se não foi fornecido ID, define mensagem de erro
    $erro = "ID do doador não informado!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Excluir Doador</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
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
                customClass: {
                    title: 'swal2-title-arial',
                    htmlContainer: 'swal2-html-arial',
                    confirmButton: 'swal2-confirm'
                }
            }).then(() => {
                window.location.href = 'consultar_doador.php';
            });
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                html: '<?= addslashes($erro) ?>',
                confirmButtonText: 'OK',
                customClass: {
                    title: 'swal2-title-arial',
                    htmlContainer: 'swal2-html-arial',
                    confirmButton: 'swal2-confirm'
                }
            }).then(() => {
                window.location.href = 'consultar_doador.php';
            });
        <?php endif; ?>
        
        // Se não houver sucesso nem erro, mostrar confirmação
        <?php if (!isset($sucesso) && !isset($erro)): ?>
            Swal.fire({
                title: 'Confirmar Exclusão',
                html: 'Tem certeza que deseja excluir este doador?<br><br><strong>Esta ação não pode ser desfeita!</strong>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim, Excluir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                customClass: {
                    title: 'swal2-title-arial',
                    htmlContainer: 'swal2-html-arial',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirecionar para a exclusão com confirmação
                    window.location.href = 'excluir_doador.php?id=<?= $id ?>&confirmado=true';
                } else {
                    // Cancelar e voltar para consulta
                    window.location.href = 'consultar_doador.php';
                }
            });
        <?php endif; ?>
        });
    </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>