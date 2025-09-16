<?php
// Define o cabeçalho para retornar JSON
header('Content-Type: application/json');

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Obtém o termo de busca via GET, usa string vazia como padrão
$termo = $_GET['termo'] ?? '';

// Se o termo estiver vazio, retorna array vazio
if (empty($termo)) {
    echo json_encode([]);
    exit;
}

try {
    // Ajustado para usar os nomes de colunas corretos (Nome em vez de Nome_Cliente)
    $sql = "SELECT Cod_Cliente as cod_cliente, Nome as nome FROM cliente WHERE Nome LIKE :termo AND status = 'ativo' ORDER BY Nome";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($clientes);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar clientes']);
}
?>
