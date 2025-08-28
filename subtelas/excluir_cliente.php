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
            echo "<script>alert('Cliente excluído com sucesso!'); window.location.href='consultar_cliente.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir cliente.'); window.location.href='consultar_cliente.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID do cliente não informado!'); window.location.href='consultar_cliente.php';</script>";
}
?>
