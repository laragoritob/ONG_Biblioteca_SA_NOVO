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
    // Consulta SQL para buscar autores que contenham o termo no nome
    // Usa LIKE com wildcards para busca parcial
    $sql = "SELECT Cod_Autor as cod_autor, Nome_Autor as nome FROM autor WHERE Nome_Autor LIKE :termo AND status = 'ativo' ORDER BY Nome_Autor";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':termo', '%' . $termo . '%', PDO::PARAM_STR);
    $stmt->execute();
    
    // Busca todos os resultados e retorna em formato JSON
    $autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($autores);
} catch (PDOException $e) {
    // Em caso de erro na consulta, retorna mensagem de erro
    echo json_encode(['error' => 'Erro ao buscar autores']);
}
?>
