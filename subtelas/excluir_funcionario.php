<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        $sql = "DELETE FROM funcionario WHERE Cod_Funcionario = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>alert('Funcionário excluído com sucesso!'); window.location.href='consultar_funcionario.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir funcionário.'); window.location.href='consultar_funcionario.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
} else {
    echo "<script>alert('ID do funcionário não informado!'); window.location.href='consultar_funcionario.php';</script>";
}
?>
