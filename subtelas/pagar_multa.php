<?php
session_start();
require_once '../conexao.php';

// VERIFICA SE O USUÁRIO TEM PERMISSÃO
if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
    exit();
}

// VERIFICA SE O ID DA MULTA FOI FORNECIDO
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID da multa não fornecido!'); window.location.href='consultar_multa.php';</script>";
    exit();
}

$cod_multa = intval($_GET['id']);

try {
    // VERIFICA SE A MULTA EXISTE E SE JÁ NÃO ESTÁ PAGA
    $sql_check = "SELECT Status_Multa FROM multa WHERE Cod_Multa = :cod_multa";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindParam(":cod_multa", $cod_multa, PDO::PARAM_INT);
    $stmt_check->execute();
    
    $multa = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$multa) {
        echo "<script>alert('Multa não encontrada!'); window.location.href='consultar_multa.php';</script>";
        exit();
    }
    
    if ($multa['Status_Multa'] === 'Paga') {
        echo "<script>alert('Esta multa já foi paga!'); window.location.href='consultar_multa.php';</script>";
        exit();
    }
    
    // ATUALIZA O STATUS DA MULTA PARA "PAGA"
    $sql_update = "UPDATE multa SET Status_Multa = 'Paga' WHERE Cod_Multa = :cod_multa";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->bindParam(":cod_multa", $cod_multa, PDO::PARAM_INT);
    
    if ($stmt_update->execute()) {
        echo "<script>
            alert('Multa marcada como paga com sucesso!');
            window.location.href='consultar_multa.php';
        </script>";
    } else {
        echo "<script>alert('Erro ao atualizar o status da multa!'); window.location.href='consultar_multa.php';</script>";
    }
    
} catch (PDOException $e) {
    echo "<script>alert('Erro no banco de dados: " . addslashes($e->getMessage()) . "'); window.location.href='consultar_multa.php';</script>";
}
?>
