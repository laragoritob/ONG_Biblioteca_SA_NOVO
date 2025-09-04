<?php
// Define o cabeçalho para retornar JSON
header('Content-Type: application/json');

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o nome de usuário do formulário e remove espaços em branco
    $usuario = trim($_POST['usuario'] ?? '');
    
    // Verifica se o usuário foi informado
    if (empty($usuario)) {
        echo json_encode(['erro' => 'Usuário não informado']);
        exit;
    }
    
    try {
        // Consulta SQL para verificar se o usuário já existe na tabela funcionario
        $sql = "SELECT COUNT(*) as total FROM funcionario WHERE usuario = :usuario AND status = 'ativo'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Verifica se o usuário existe (total > 0)
        $usuarioExiste = $resultado['total'] > 0;
        
        // Retorna o resultado da verificação em formato JSON
        echo json_encode([
            'usuario_existe' => $usuarioExiste,
            'mensagem' => $usuarioExiste ? 'Usuário já cadastrado' : 'Usuário disponível'
        ]);
        
    } catch (PDOException $e) {
        // Em caso de erro na consulta, retorna mensagem de erro
        echo json_encode(['erro' => 'Erro ao verificar usuário: ' . $e->getMessage()]);
    }
} else {
    // Se não for método POST, retorna erro
    echo json_encode(['erro' => 'Método não permitido']);
}
?>
