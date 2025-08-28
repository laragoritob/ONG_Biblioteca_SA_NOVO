<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM autor WHERE Cod_Autor = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Autor excluído com sucesso!'); window.location.href='consultar_autor.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir autor.'); window.location.href='consultar_autor.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID do autor não informado!'); window.location.href='consultar_autor.php';</script>";
}
?>
