<?php
session_start();
require_once '../conexao.php';

// Verificar se é uma confirmação ou execução
$confirmado = isset($_GET['confirmado']) && $_GET['confirmado'] === 'true';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($confirmado) {
        // Executar a exclusão
        try {
            // Primeiro verificar se o cliente existe
            $sql_check = "SELECT Nome FROM cliente WHERE Cod_Cliente = :id";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt_check->execute();
            
            $cliente = $stmt_check->fetch(PDO::FETCH_ASSOC);
            
            if (!$cliente) {
                $erro = "Cliente não encontrado!";
            } else {
                // Excluir o cliente
                $sql = "DELETE FROM cliente WHERE Cod_Cliente = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $sucesso = "Cliente " . htmlspecialchars($cliente['Nome']) . " excluído com sucesso!";
                } else {
                    $erro = "Erro ao excluir cliente.";
                }
            }
        } catch (PDOException $e) {
            $erro = "Erro ao excluir cliente: " . $e->getMessage();
        }
    }
    // Se não for confirmado, apenas mostrar a confirmação
} else {
    $erro = "ID do cliente não informado!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Excluir Cliente</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
                    window.location.href = 'consultar_cliente.php';
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
                    window.location.href = 'consultar_cliente.php';
                });
            <?php endif; ?>
            
            // Se não houver sucesso nem erro, mostrar confirmação
            <?php if (!isset($sucesso) && !isset($erro)): ?>
                Swal.fire({
                    title: 'Confirmar Exclusão',
                    html: 'Tem certeza que deseja excluir este cliente?<br><br><strong>Esta ação não pode ser desfeita!</strong>',
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
                        window.location.href = 'excluir_cliente.php?id=<?= $id ?>&confirmado=true';
                    } else {
                        // Cancelar e voltar para consulta
                        window.location.href = 'consultar_cliente.php';
                    }
                });
            <?php endif; ?>
        });
    </script>
</body>
</html>
