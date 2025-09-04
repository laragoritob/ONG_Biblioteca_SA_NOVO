<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se foi fornecido um ID de autor via GET
if (isset($_GET['id_autor'])) {
    // Obtém o ID do autor da requisição
    $id_autor = $_GET['id_autor'];
    
    try {
        // Consulta SQL para buscar o nome do autor pelo ID
        $sql = "SELECT Nome_Autor FROM autor WHERE Cod_Autor = :id_autor AND status = 'ativo'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_autor', $id_autor);
        $stmt->execute();
        
        // Busca o resultado da consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se encontrou o autor
        if ($resultado) {
            // Retorna sucesso com o nome do autor em formato JSON
            echo json_encode(['success' => true, 'nome_autor' => $resultado['Nome_Autor']]);
        } else {
            // Retorna erro se não encontrou o autor
            echo json_encode(['success' => false, 'message' => 'Autor não encontrado']);
        }
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna mensagem de erro
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    // Se não foi fornecido ID, retorna erro
    echo json_encode(['success' => false, 'message' => 'ID do autor não fornecido']);
}
?>
