<?php
require_once '../conexao.php';

if (isset($_GET['id_autor'])) {
    $id_autor = $_GET['id_autor'];
    
    try {
        $sql = "SELECT Nome_Autor FROM autor WHERE Cod_Autor = :id_autor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_autor', $id_autor);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo json_encode(['success' => true, 'nome_autor' => $resultado['Nome_Autor']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Autor não encontrado']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID do autor não fornecido']);
}
?>
