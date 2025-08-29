<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM editora WHERE Cod_Editora = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Editora excluída com sucesso!'); window.location.href='consultar_editora.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir editora.'); window.location.href='consultar_editora.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID da editora não informado!'); window.location.href='consultar_editora.php';</script>";
}
?>
