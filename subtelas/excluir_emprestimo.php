<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM emprestimo WHERE Cod_Emprestimo = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Empréstimo excluído com sucesso!'); window.location.href='consultar_emprestimo.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir empréstimo.'); window.location.href='consultar_emprestimo.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID do empréstimo não informado!'); window.location.href='consultar_emprestimo.php';</script>";
}
?>