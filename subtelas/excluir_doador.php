<?php
session_start();
require_once '../conexao.php';

// Inicializar variáveis de notificação
$sucesso = null;
$erro = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Primeiro verificar se o doador existe
        $sql_check = "SELECT Nome_Doador FROM doador WHERE Cod_Doador = :id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check->execute();
        
        $doador = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if (!$doador) {
            $erro = "Doador não encontrado!";
        } else {
            // Excluir o doador
            $sql = "DELETE FROM doador WHERE Cod_Doador = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $sucesso = "Doador " . htmlspecialchars($doador['Nome_Doador']) . " excluído com sucesso!";
            } else {
                $erro = "Erro ao excluir doador.";
            }
        }
    } catch (PDOException $e) {
        $erro = "Erro ao excluir doador: " . $e->getMessage();
    }
} else {
    $erro = "ID do doador não informado!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Excluir Doador</title>
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
    </style>
</head>
<body>
    <script>
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
    </script>
</body>
</html>