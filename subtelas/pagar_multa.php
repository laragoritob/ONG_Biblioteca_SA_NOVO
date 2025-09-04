<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Apenas Gerente (perfil 1) pode pagar multas
if ($_SESSION['perfil'] != 1) {
    // Se não tem permissão, exibe alerta e redireciona
    echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
    exit();
}

// Verifica se o ID da multa foi fornecido via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Se não foi fornecido ID, exibe alerta e redireciona
    echo "<script>alert('ID da multa não fornecido!'); window.location.href='consultar_multa.php';</script>";
    exit();
}

// Converte o ID da multa para inteiro para segurança (previne SQL injection)
$cod_multa = intval($_GET['id']);

try {
    // Consulta SQL para verificar se a multa existe e se já não está paga
    $sql_check = "SELECT Status_Multa FROM multa WHERE Cod_Multa = :cod_multa";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(":cod_multa", $cod_multa, PDO::PARAM_INT);
    $stmt_check->execute();
    
    $multa = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    // Verifica se encontrou a multa
    if (!$multa) {
        echo "<script>alert('Multa não encontrada!'); window.location.href='consultar_multa.php';</script>";
        exit();
    }
    
    // Verifica se a multa já foi paga
    if ($multa['Status_Multa'] === 'Paga') {
        echo "<script>alert('Esta multa já foi paga!'); window.location.href='consultar_multa.php';</script>";
        exit();
    }
    
    // Atualiza o status da multa para "Paga"
    $sql_update = "UPDATE multa SET Status_Multa = 'Paga' WHERE Cod_Multa = :cod_multa";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(":cod_multa", $cod_multa, PDO::PARAM_INT);
    
    // Executa a atualização e verifica o resultado
    if ($stmt_update->execute()) {
        // Se sucesso, exibe mensagem de sucesso e redireciona
        echo "<script>
            alert('Multa marcada como paga com sucesso!');
            window.location.href='consultar_multa.php';
        </script>";
    } else {
        // Se falhou, exibe mensagem de erro e redireciona
        echo "<script>alert('Erro ao atualizar o status da multa!'); window.location.href='consultar_multa.php';</script>";
    }
    
} catch (PDOException $e) {
    // Em caso de erro na execução, captura e exibe a mensagem
    echo "<script>alert('Erro no banco de dados: " . addslashes($e->getMessage()) . "'); window.location.href='consultar_multa.php';</script>";
}
?>
