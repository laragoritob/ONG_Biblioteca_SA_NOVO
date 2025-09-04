<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se foi fornecido um ID de livro via GET
if (isset($_GET['id_livro'])) {
    // Obtém o ID do livro da requisição
    $id_livro = $_GET['id_livro'];
    try {
        // Consulta SQL para buscar o título do livro pelo ID
        $sql = "SELECT titulo FROM livro WHERE cod_livro = :id_livro";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_livro', $id_livro);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se encontrou o livro
        if ($resultado) {
            // Retorna sucesso com o título do livro em formato JSON
            echo json_encode(['success' => true, 'titulo' => $resultado['titulo']]);
        } else {
            // Retorna erro se não encontrou o livro
            echo json_encode(['success' => false, 'message' => 'Livro não encontrado']);
        }
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna mensagem de erro
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    // Se não foi fornecido ID, retorna erro
    echo json_encode(['success' => false, 'message' => 'ID do livro não fornecido']);
}
?>
