<?php
require_once '../conexao.php';

if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];
    try {
        $sql = "SELECT nome FROM cliente WHERE cod_cliente = :id_cliente";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'nome' => $resultado['nome']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cliente não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do cliente não fornecido']);
}
?>
