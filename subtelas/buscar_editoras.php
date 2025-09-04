<?php
header('Content-Type: application/json');
require_once '../conexao.php';

$termo = $_GET['termo'] ?? '';

if (empty($termo)) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT Cod_Editora as cod_editora, Nome_Editora as nome FROM editora WHERE Nome_Editora LIKE :termo ORDER BY Nome_Editora";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $editoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($editoras);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar editoras']);
}
?>
