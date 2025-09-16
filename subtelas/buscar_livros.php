<?php
header('Content-Type: application/json');
require_once '../conexao.php';
$termo = $_GET['termo'] ?? '';
if ($termo === '') { echo json_encode([]); exit; }
try {
    $sql = "SELECT Cod_Livro AS cod_livro, Titulo AS titulo FROM livro WHERE status = 'ativo' AND Titulo LIKE :termo ORDER BY Titulo";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
    $stmt->execute();
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    echo json_encode(['error' => 'Erro ao buscar livros']);
}
?>
