<?php
// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se foi fornecido um ID de cliente via GET
if (isset($_GET['id_cliente'])) {
    // Obtém o ID do cliente da requisição
    $id_cliente = $_GET['id_cliente'];
    try {
        // Consulta SQL para buscar o nome do cliente pelo ID
        $sql = "SELECT nome FROM cliente WHERE cod_cliente = :id_cliente AND status = 'ativo'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se encontrou o cliente
        if ($resultado) {
            // Retorna sucesso com o nome do cliente em formato JSON
            echo json_encode(['success' => true, 'nome' => $resultado['nome']]);
        } else {
            // Retorna erro se não encontrou o cliente
            echo json_encode(['success' => false, 'message' => 'Cliente não encontrado']);
        }
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna mensagem de erro
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    // Se não foi fornecido ID, retorna erro
    echo json_encode(['success' => false, 'message' => 'ID do cliente não fornecido']);
}
?>
