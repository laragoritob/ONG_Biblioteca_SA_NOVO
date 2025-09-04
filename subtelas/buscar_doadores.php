<?php
header('Content-Type: application/json');
require_once '../conexao.php';

$termo = $_GET['termo'] ?? '';

if (empty($termo)) {
    echo json_encode([]);
    exit;
}

try {
    $sql = "SELECT Cod_Doador as cod_doador, Nome_Doador as nome FROM doador WHERE Nome_Doador LIKE :termo ORDER BY Nome_Doador";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    $doadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($doadores);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar doadores']);
}
?>
