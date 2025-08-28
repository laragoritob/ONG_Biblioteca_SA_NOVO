<?php
session_start();
require_once '../conexao.php';

// Verificar permissão do usuário
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso negado!'); window.location.href='../gerente.php';</script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    try {
        // Primeiro verificar se o livro existe
        $sql_check = "SELECT Cod_Livro, Titulo FROM livro WHERE Cod_Livro = :id";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_check->execute();
        
        $livro = $stmt_check->fetch(PDO::FETCH_ASSOC);
        
        if (!$livro) {
            echo "<script>alert('Livro não encontrado!'); window.location.href='consultar_livro.php';</script>";
            exit;
        }

        // Verificar se o livro está emprestado
        $sql_emprestimo = "SELECT COUNT(*) as total FROM emprestimo WHERE Cod_Livro = :id AND Data_Devolucao IS NULL";
        $stmt_emprestimo = $pdo->prepare($sql_emprestimo);
        $stmt_emprestimo->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_emprestimo->execute();
        $emprestimo = $stmt_emprestimo->fetch(PDO::FETCH_ASSOC);

        if ($emprestimo['total'] > 0) {
            echo "<script>alert('Não é possível excluir este livro pois ele está emprestado!'); window.location.href='consultar_livro.php';</script>";
            exit;
        }

        // Excluir o livro
        $sql = "DELETE FROM livro WHERE Cod_Livro = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                alert('Livro excluído com sucesso!');
                window.location.href='consultar_livro.php';
            </script>";
        } else {
            echo "<script>alert('Erro ao excluir livro.'); window.location.href='consultar_livro.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Erro ao excluir livro: " . addslashes($e->getMessage()) . "'); window.location.href='consultar_livro.php';</script>";
    }
} else {
    echo "<script>alert('ID do livro não informado!'); window.location.href='consultar_livro.php';</script>";
}
?>