<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM cliente WHERE Cod_Cliente = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $sucesso = "Cliente excluído com sucesso!";
            $redirect = "consultar_cliente.php";
        } else {
            $erro = "Erro ao excluir cliente.";
            $redirect = "consultar_cliente.php";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    $erro = "ID do cliente não informado!";
    $redirect = "consultar_cliente.php";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Excluir Cliente</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/notification-modal.css">
</head>
<body>
    <script src="subtelas_javascript/notification-modal.js"></script>
    <script>
        // Mostrar notificações baseadas no PHP
        <?php if (isset($sucesso)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('success', 'Sucesso!', '<?= addslashes($sucesso) ?>');
                // Redirecionar após 2 segundos
                setTimeout(function() {
                    window.location.href = '<?= $redirect ?>';
                }, 2000);
            });
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('error', 'Erro!', '<?= addslashes($erro) ?>');
                // Redirecionar após 2 segundos
                setTimeout(function() {
                    window.location.href = '<?= $redirect ?>';
                }, 2000);
            });
        <?php endif; ?>
    </script>
</body>
</html>
