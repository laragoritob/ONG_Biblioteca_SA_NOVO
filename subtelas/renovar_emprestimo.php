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
          e.Data_Ultima_Renovacao,
          c.Nome as Nome_Cliente,
          l.Titulo as Nome_Livro
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

// Verifica se já foi renovado (se Data_Ultima_Renovacao não é nula)
$ja_renovado = !empty($emprestimo['Data_Ultima_Renovacao']);

// Processar formulário de renovação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renovar'])) {
    // Verificar se já foi renovado
    if ($ja_renovado) {
        $erro = "Este empréstimo já foi renovado anteriormente. Apenas uma renovação é permitida.";
    } else {
        try {
            // Calcular nova data de devolução (7 dias a partir da data atual)
            $nova_data_devolucao = date('Y-m-d', strtotime('+7 days'));
            
            $sql_update = "UPDATE emprestimo 
                          SET Data_Devolucao = :data_devolucao,
                              Data_Ultima_Renovacao = NOW()
                          WHERE Cod_Emprestimo = :id";
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':data_devolucao', $nova_data_devolucao);
            $stmt_update->bindParam(':id', $id);
            
            if ($stmt_update->execute()) {
                $sucesso = "Empréstimo renovado com sucesso! Nova data de devolução: " . date('d/m/Y', strtotime($nova_data_devolucao));
                // Recarregar dados do emprestimo
                $stmt->execute();
                $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);
                $ja_renovado = true; // Atualiza status local
            } else {
                $erro = "Erro ao renovar empréstimo";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao renovar empréstimo: " . $e->getMessage();
        }
    }
}

// Processar formulário de alteração manual (apenas para administradores)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_manual'])) {
    // Verificar se usuário tem permissão de administrador
    if ($_SESSION['perfil'] !== 'administrador') {
        $erro = "Apenas administradores podem alterar manualmente datas de devolução.";
    } else {
        $data_devolucao = trim($_POST['data_devolucao']);
        
        if (empty($data_devolucao)) {
            $erro = "Data de devolução é obrigatória";
        } else {
            // Validar formato da data
            $data_obj = DateTime::createFromFormat('Y-m-d', $data_devolucao);
            if (!$data_obj) {
                $erro = "Formato de data inválido. Use YYYY-MM-DD";
            } else {
                try {
                    $sql_update = "UPDATE emprestimo 
                                  SET Data_Devolucao = :data_devolucao
                                  WHERE Cod_Emprestimo = :id";
                    
                    $stmt_update = $pdo->prepare($sql_update);
                    $stmt_update->bindParam(':data_devolucao', $data_devolucao);
                    $stmt_update->bindParam(':id', $id);
                    
                    if ($stmt_update->execute()) {
                        $sucesso = "Data de devolução alterada com sucesso!";
                        // Recarregar dados do emprestimo
                        $stmt->execute();
                        $emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);
                    } else {
                        $erro = "Erro ao alterar data de devolução";
                    }
                } catch (PDOException $e) {
                    $erro = "Erro ao alterar data de devolução: " . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Data de Devolução</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-title-arial {
            font-family: Arial, sans-serif !important;
            font-weight: bold !important;
        }
        
        .swal2-html-arial {
            font-family: Arial, sans-serif !important;
            font-size: 16px !important;
        }
        
        /* Estilo dos botões igual ao cadastro_funcionario */
        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:focus {
            outline: 2px solid #6366f1 !important;
            outline-offset: 2px !important;
        }
        
        .swal2-cancel {
            background: #dc2626 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-cancel:hover {
            background: #b91c1c !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }

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
        
        .date-input-container {
            margin-top: 1.5rem;
            padding: 1.5rem;
            background: #f9fafb;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        
        .date-input-container h3 {
            margin-top: 0;
            margin-bottom: 1rem;
            color: #374151;
            font-size: 1.1rem;
        }
        
        .date-input-row {
            display: flex;
            gap: 1rem;
            align-items: flex-end;
        }
        
        .renovation-status {
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            font-weight: 600;
        }
        
        .renovation-available {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        
        .renovation-used {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        
        .admin-only {
            background-color: #e0e7ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
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
            
            .date-input-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="main-container">
        <div class="header">
            <h1>Alterar Data de Devolução #<?php echo $emprestimo['Cod_Emprestimo']; ?></h1>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-error" style="display: none;">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success" style="display: none;">
                <?php echo $sucesso; ?>
            </div>
        <?php endif; ?>

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
                                 <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                 <circle cx="12" cy="7" r="4"></circle>
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
                        <label>Data de Devolução Atual</label>
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
                
                <?php if (!empty($emprestimo['Data_Ultima_Renovacao'])): ?>
                    <div class="input-group">
                        <label>Última Renovação</label>
                        <div class="input-wrapper">
                            <input type="text" value="<?php echo date('d/m/Y', strtotime($emprestimo['Data_Ultima_Renovacao'])); ?>" readonly>
                            <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Status da Renovação -->
                <div class="form-row">
                    <div class="renovation-status <?php echo ($ja_renovado) ? 'renovation-used' : 'renovation-available'; ?>">
                        <?php if ($ja_renovado): ?>
                            ⚠️ Este empréstimo já foi renovado. Apenas uma renovação é permitida.
                        <?php else: ?>
                            ✅ Renovação disponível (apenas uma renovação permitida por empréstimo)
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!$ja_renovado): ?>
            <div class="form-row">
                <div class="date-input-row">
                    <button type="button" onclick="renovarEmprestimo()" class="btn btn-primary" style="margin-bottom: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        Renovar Empréstimo (+7 dias)
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($_SESSION['perfil'] === 'administrador'): ?>
            <div class="admin-only">
                <h3>⚙️ Acesso de Administrador</h3>
                <p>Como administrador, você pode alterar manualmente a data de devolução.</p>
                
                <div class="date-input-container">
                    <h3>Alteração Manual da Data</h3>
                    <div class="date-input-row">
                        <div class="input-group">
                            <label>Nova Data de Devolução</label>
                            <div class="input-wrapper">
                                <input type="date" id="data_devolucao" name="data_devolucao" value="<?php echo date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                        <button type="button" onclick="alterarDataManual()" class="btn btn-secondary" style="margin-bottom: 0;">
                            Alterar Data Manualmente
                        </button>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="form-actions">
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
        // Mostrar notificações baseadas no PHP
        <?php if (isset($sucesso)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Sucesso!',
                    text: '<?= addslashes($sucesso) ?>',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
            });
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: '<?= addslashes($erro) ?>',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
            });
        <?php endif; ?>

        function renovarEmprestimo() {
            Swal.fire({
                title: 'Confirmar Renovação',
                text: 'Deseja renovar o empréstimo por mais 7 dias? Esta ação só pode ser realizada uma vez.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, Renovar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    title: 'swal2-title-arial',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Criar e enviar formulário para renovação
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = '<input type="hidden" name="renovar" value="1">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function alterarDataManual() {
            const data_devolucao = document.getElementById('data_devolucao').value.trim();
            
            if (data_devolucao === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'A data de devolução é obrigatória!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            Swal.fire({
                title: 'Confirmar Alteração Manual',
                text: 'Tem certeza que deseja alterar manualmente a data de devolução?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, Alterar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    title: 'swal2-title-arial',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Criar e enviar formulário para alteração manual
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = '<input type="hidden" name="alterar_manual" value="1"><input type="hidden" name="data_devolucao" value="' + data_devolucao + '">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>