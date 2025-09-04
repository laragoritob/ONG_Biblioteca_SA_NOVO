<?php
header('Content-Type: application/json');
require_once '../conexao.php';

$termo = $_GET['termo'] ?? '';

if (empty($termo)) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT Cod_Autor as cod_autor, Nome_Autor as nome FROM autor WHERE Nome_Autor LIKE :termo ORDER BY Nome_Autor";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($autores);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar autores']);
}
?>
