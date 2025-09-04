<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se foi fornecido um ID de editora via GET
if (isset($_GET['id_editora'])) {
    // Obtém o ID da editora da requisição
    $id_editora = $_GET['id_editora'];
    
    try {
        // Consulta SQL para buscar o nome da editora pelo ID
        $sql = "SELECT Nome_Editora FROM editora WHERE Cod_Editora = :id_editora AND status = 'ativo'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_editora', $id_editora);
        $stmt->execute();
        
        // Busca o resultado da consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se encontrou a editora
        if ($resultado) {
            // Retorna sucesso com o nome da editora em formato JSON
            echo json_encode(['success' => true, 'nome_editora' => $resultado['Nome_Editora']]);
        } else {
            // Retorna erro se não encontrou a editora
            echo json_encode(['success' => false, 'message' => 'Editora não encontrada']);
        }
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna mensagem de erro
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    // Se não foi fornecido ID, retorna erro
    echo json_encode(['success' => false, 'message' => 'ID da editora não fornecido']);
}
?>
