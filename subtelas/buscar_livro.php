<?php
require_once '../conexao.php';

if (isset($_GET['id_livro'])) {
    $id_livro = $_GET['id_livro'];
    try {
        $sql = "SELECT titulo FROM livro WHERE cod_livro = :id_livro";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_livro', $id_livro);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'titulo' => $resultado['titulo']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Livro não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do livro não fornecido']);
}
?>
