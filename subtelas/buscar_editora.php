<?php
require_once '../conexao.php';

if (isset($_GET['id_editora'])) {
    $id_editora = $_GET['id_editora'];
    
    try {
        $sql = "SELECT Nome_Editora FROM editora WHERE Cod_Editora = :id_editora";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_editora', $id_editora);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'nome_editora' => $resultado['Nome_Editora']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Editora não encontrada']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID da editora não fornecido']);
}
?>
