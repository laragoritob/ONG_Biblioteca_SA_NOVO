<?php
require_once '../conexao.php';

if (isset($_GET['id_doador'])) {
    $id_doador = $_GET['id_doador'];
    
    try {
        $sql = "SELECT Nome_Doador FROM doador WHERE Cod_Doador = :id_doador";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_doador', $id_doador);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'nome_doador' => $resultado['Nome_Doador']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Doador não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do doador não fornecido']);
}
?>
