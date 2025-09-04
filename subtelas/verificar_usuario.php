<?php
header('Content-Type: application/json');
require_once '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    
    if (empty($usuario)) {
        echo json_encode(['erro' => 'Usuário não informado']);
        exit;
    }
    
    try {
        // Verificar se o usuário já existe na tabela funcionario
        $sql = "SELECT COUNT(*) as total FROM funcionario WHERE usuario = :usuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $usuarioExiste = $resultado['total'] > 0;
        
        echo json_encode([
            'usuario_existe' => $usuarioExiste,
            'mensagem' => $usuarioExiste ? 'Usuário já cadastrado' : 'Usuário disponível'
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['erro' => 'Erro ao verificar usuário: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['erro' => 'Método não permitido']);
}
?>
