<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se foi passado um ID de empréstimo via GET
if (!isset($_GET['id'])) {
    // Se não foi fornecido ID, redireciona para a página de consulta
    header('Location: consultar_emprestimo.php');
    exit;
}

// Converte o ID para inteiro para segurança (previne SQL injection)
$id = intval($_GET['id']);

// Consulta SQL para buscar dados do empréstimo com informações relacionadas
$sql = "SELECT 
          e.Cod_Emprestimo,
          e.Data_Emprestimo,
          e.Data_Devolucao,
          c.Nome as Nome_Cliente,
          c.CPF as CPF_Cliente,
          l.Titulo as Nome_Livro,
          l.Cod_Livro,
          l.Quantidade
        FROM emprestimo e
        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente
        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro
        WHERE e.Cod_Emprestimo = :id";

try {
    // Prepara e executa a consulta para buscar os dados do empréstimo
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Verifica se encontrou o empréstimo
    if (!$emprestimo) {
        // Se não encontrou, redireciona para a página de consulta
        header('Location: consultar_emprestimo.php');
        exit;
    }
} catch (PDOException $e) {
    // Em caso de erro na consulta, exibe mensagem e para execução
    die("Erro na consulta: " . $e->getMessage());
}

// Calcular status do empréstimo
$hoje = date('Y-m-d');
$data_devolucao = $emprestimo['Data_Devolucao'];
$status = '';

if ($data_devolucao < $hoje) {
    $dias_atraso = (strtotime($hoje) - strtotime($data_devolucao)) / (60 * 60 * 24);
    $multa_calculada = $dias_atraso * 2.00;
    $status = 'ATRASADO';
} elseif ($data_devolucao == $hoje) {
    $status = 'VENCE HOJE';
} else {
    $status = 'NO PRAZO';
}

// Processar devolução
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['devolver'])) {
    try {
        $pdo->beginTransaction();
        
        $data_devolucao_real = date('Y-m-d');
        $data_devolucao_prevista = $emprestimo['Data_Devolucao'];
        
        // Calcular multa se houver atraso
        $multa = 0;
        $dias_atraso = 0;
        
        if ($data_devolucao_real > $data_devolucao_prevista) {
            $data_prevista = new DateTime($data_devolucao_prevista);
            $data_real = new DateTime($data_devolucao_real);
            $dias_atraso = $data_real->diff($data_prevista)->days;
            $multa = $dias_atraso * 2.00; // R$ 2,00 por dia de atraso
        }
        
        // Inserir multa se houver
        if ($multa > 0) {
            $sql_multa = "INSERT INTO multa (Cod_Emprestimo, Data_Multa, Valor_Multa) VALUES (:cod_emprestimo, :data_multa, :valor_multa)";
            $stmt_multa = $pdo->prepare($sql_multa);
            $stmt_multa->bindParam(':cod_emprestimo', $id, PDO::PARAM_INT);
            $stmt_multa->bindParam(':data_multa', $data_devolucao_real);
            $stmt_multa->bindParam(':valor_multa', $multa);
            $stmt_multa->execute();
        }
        
        // Atualizar quantidade do livro (devolver ao acervo)
        $nova_quantidade = $emprestimo['Quantidade'] + 1;
        $sql_livro = "UPDATE livro SET Quantidade = :quantidade WHERE Cod_Livro = :cod_livro";
        $stmt_livro = $pdo->prepare($sql_livro);
        $stmt_livro->bindParam(':quantidade', $nova_quantidade);
        $stmt_livro->bindParam(':cod_livro', $emprestimo['Cod_Livro']);
        $stmt_livro->execute();
        
        // Marcar o empréstimo como devolvido (não deletar)
        $sql_update = "UPDATE emprestimo SET Status_Emprestimo = 'Devolvido' WHERE Cod_Emprestimo = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_update->execute();
        
        $pdo->commit();
        
        // Redirecionar com mensagem de sucesso
        if ($multa > 0) {
            header('Location: consultar_emprestimo.php?sucesso=1&multa=' . $multa . '&dias=' . $dias_atraso);
        } else {
            header('Location: consultar_emprestimo.php?sucesso=1');
        }
        exit;
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        header('Location: consultar_emprestimo.php?erro=' . urlencode($e->getMessage()));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devolver Empréstimo</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }
        
        .main-container {
            width: 100%;
            max-width: 800px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .header {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 100%;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #480b85;
            margin: 0;
        }
        
        .formulario {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            width: 100%;
            border: 1px solid #e5e7eb;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .status-atrasado {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .status-vencendo {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fed7aa;
        }
        
        .status-ok {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        
        .multa-info {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem 0;
            color: #dc2626;
        }
        
        .multa-info h4 {
            margin: 0 0 0.5rem 0;
            color: #dc2626;
        }
        
        .multa-info p {
            margin: 0.25rem 0;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 0.5rem;
            }
            
            .header {
                padding: 1rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .formulario {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <h1>Devolver Empréstimo #<?php echo $emprestimo['Cod_Emprestimo']; ?></h1>
        </div>

        <div class="formulario">
            <div class="form-section">
                <div class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                    </svg>
                    Informações do Empréstimo
                </div>
                
                <div class="form-row">
                    <div class="input-group">
                        <label>Código do Empréstimo</label>
                        <div class="input-wrapper">
                            <input type="text" value="<?php echo $emprestimo['Cod_Emprestimo']; ?>" readonly>
                            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Cliente</label>
                        <div class="input-wrapper">
                            <input type="text" value="<?php echo $emprestimo['Nome_Cliente']; ?>" readonly>
                            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="input-group">
                        <label>Livro</label>
                        <div class="input-wrapper">
                            <input type="text" value="<?php echo $emprestimo['Nome_Livro']; ?>" readonly>
                            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40">
                                <path d="M192 576L512 576C529.7 576 544 561.7 544 544C544 526.3 529.7 512 512 512L512 445.3C530.6 438.7 544 420.9 544 400L544 112C544 85.5 522.5 64 496 64L448 64L448 233.4C448 245.9 437.9 256 425.4 256C419.4 256 413.6 253.6 409.4 249.4L368 208L326.6 249.4C322.4 253.6 316.6 256 310.6 256C298.1 256 288 245.9 288 233.4L288 64L192 64C139 64 96 107 96 160L96 480C96 533 139 576 192 576zM160 480C160 462.3 174.3 448 192 448L448 448L448 512L192 512C174.3 512 160 497.7 160 480z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Data do Empréstimo</label>
                        <div class="input-wrapper">
                            <input type="text" value="<?php echo date('d/m/Y', strtotime($emprestimo['Data_Emprestimo'])); ?>" readonly>
                            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="input-group">
                        <label>Data de Devolução Prevista</label>
                        <div class="input-wrapper">
                            <input type="text" value="<?php echo date('d/m/Y', strtotime($emprestimo['Data_Devolucao'])); ?>" readonly>
                            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                    </div>
                    <div class="input-group">
                        <label>Status</label>
                        <div class="input-wrapper">
                            <span class="status-badge <?php echo $status === 'ATRASADO' ? 'status-atrasado' : ($status === 'VENCE HOJE' ? 'status-vencendo' : 'status-ok'); ?>">
                                <?php echo $status; ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <?php if ($status === 'ATRASADO'): ?>
                    <div class="multa-info">
                        <h4>⚠️ Multa por Atraso</h4>
                        <p><strong>Dias de atraso:</strong> <?php echo $dias_atraso; ?> dias</p>
                        <p><strong>Valor da multa:</strong> R$ <?php echo number_format($multa_calculada, 2, ',', '.'); ?></p>
                        <p><strong>Taxa:</strong> R$ 2,00 por dia de atraso</p>
                    </div>
                <?php elseif ($status === 'VENCE HOJE'): ?>
                    <div class="multa-info" style="background: #fffbeb; border-color: #fed7aa; color: #d97706;">
                        <h4>⚠️ Vence Hoje</h4>
                        <p>Este empréstimo vence hoje. Devolva para evitar multas.</p>
                    </div>
                <?php else: ?>
                    <div class="multa-info" style="background: #f0fdf4; border-color: #bbf7d0; color: #16a34a;">
                        <h4>✅ No Prazo</h4>
                        <p>Este empréstimo está dentro do prazo de devolução.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <form method="POST" onsubmit="return confirmarDevolucao()" style="display: inline;">
                    <button type="submit" name="devolver" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Confirmar Devolução
                    </button>
                </form>
                
                <a href="consultar_emprestimo.php" class="btn btn-back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <script>
        function confirmarDevolucao() {
            const status = '<?php echo $status; ?>';
            let mensagem = '';
            
            if (status === 'ATRASADO') {
                const multa = '<?php echo number_format($multa_calculada, 2, ',', '.'); ?>';
                const dias = '<?php echo $dias_atraso; ?>';
                mensagem = `Este empréstimo está atrasado e será aplicada uma multa de R$ ${multa} (${dias} dias de atraso). Confirma a devolução?`;
            } else if (status === 'VENCE HOJE') {
                mensagem = 'Este empréstimo vence hoje. Confirma a devolução?';
            } else {
                mensagem = 'Confirma a devolução deste empréstimo?';
            }
            
            return Swal.fire({
                title: 'Confirmar Devolução',
                text: mensagem,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, Devolver',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ffbcfc',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    return true;
                } else {
                    return false;
                }
            });
            return false;
        }
    </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>
