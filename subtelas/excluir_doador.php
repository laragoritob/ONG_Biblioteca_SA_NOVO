<?php
session_start();
require_once '../conexao.php';

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
            echo "<script>alert('Doador não encontrado!'); window.location.href='consultar_doador.php';</script>";
            exit;
        }

        // Excluir o doador
        $sql = "DELETE FROM doador WHERE Cod_Doador = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                alert('Doador " . htmlspecialchars($doador['Nome_Doador']) . " excluído com sucesso!');
                window.location.href='consultar_doador.php';
            </script>";
        } else {
            echo "<script>alert('Erro ao excluir doador.'); window.location.href='consultar_doador.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir doador: " . addslashes($e->getMessage()) . "'); window.location.href='consultar_doador.php';</script>";
    }
} else {
    echo "<script>alert('ID do doador não informado!'); window.location.href='consultar_doador.php';</script>";
}
?>