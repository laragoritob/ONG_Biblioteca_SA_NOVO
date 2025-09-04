<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se foi fornecido um ID de doador via GET
if (isset($_GET['id_doador'])) {
    // Obtém o ID do doador da requisição
    $id_doador = $_GET['id_doador'];
    
    try {
        // Consulta SQL para buscar o nome do doador pelo ID
        $sql = "SELECT Nome_Doador FROM doador WHERE Cod_Doador = :id_doador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_doador', $id_doador);
        $stmt->execute();
        
        // Busca o resultado da consulta
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se encontrou o doador
        if ($resultado) {
            // Retorna sucesso com o nome do doador em formato JSON
            echo json_encode(['success' => true, 'nome_doador' => $resultado['Nome_Doador']]);
        } else {
            // Retorna erro se não encontrou o doador
            echo json_encode(['success' => false, 'message' => 'Doador não encontrado']);
        }
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna mensagem de erro
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    // Se não foi fornecido ID, retorna erro
    echo json_encode(['success' => false, 'message' => 'ID do doador não fornecido']);
}
?>
