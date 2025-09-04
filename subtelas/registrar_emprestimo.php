<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Gerente (perfil 1), Bibliotecário (perfil 3) e Recreador (perfil 4) podem registrar empréstimos
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4) {
    // Se não tem permissão, exibe alerta e redireciona para login
    echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
    exit();
}

// Define qual página o usuário deve retornar baseado em seu perfil
switch ($_SESSION['perfil']) {
    case 1: // Gerente - pode acessar todas as funcionalidades
        $linkVoltar = "../gerente.php";
        break;
    case 2: // Gestor - não tem acesso a esta página
        $linkVoltar = "../gestor.php";
        break;
    case 3: // Bibliotecário - pode registrar empréstimos
        $linkVoltar = "../bibliotecario.php";
        break;
    case 4: // Recreador - pode registrar empréstimos
        $linkVoltar = "../recreador.php";
        break;
    case 5: // Repositor - não tem acesso a esta página
        $linkVoltar = "../repositor.php";
        break;
    default:
        // Se perfil não for reconhecido, redireciona para login
        $linkVoltar = "../index.php";
        break;
}

// Executado apenas quando o formulário é enviado via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $cod_livro = $_POST['cod_livro'];
    $cod_cliente = $_POST['cod_cliente'];
    $data_emprestimo = $_POST['data_emprestimo'];
    $data_devolucao = $_POST['data_devolucao'];

    // Query SQL para inserir o novo empréstimo no banco de dados
    $sql = "INSERT INTO emprestimo (cod_livro,cod_cliente,data_emprestimo,data_devolucao) 
                VALUES (:cod_livro,:cod_cliente,:data_emprestimo,:data_devolucao)";

    // Prepara a query usando prepared statement para segurança
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cod_livro', $cod_livro);
    $stmt->bindParam(':cod_cliente', $cod_cliente);
    $stmt->bindParam(':data_emprestimo', $data_emprestimo);
    $stmt->bindParam(':data_devolucao', $data_devolucao);

    // Executa a inserção e verifica o resultado
    if ($stmt->execute()) {
        // Se sucesso, define mensagem de sucesso
        $sucesso = "Empréstimo cadastrado com sucesso!";
    } else {
        // Se falhou, define mensagem de erro
        $erro = "Erro ao cadastrar empréstimo!";
    }
}

// Define data atual como padrão para data de empréstimo
$data_atual = date('Y-m-d');
// Define data de devolução como uma semana após a data atual
$data_devolucao_padrao = date('Y-m-d', strtotime('+1 week'));
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="page-wrapper">
        <header class="header">
            <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
            </a>
            <h1>FAZER EMPRÉSTIMO</h1>
        </header>
        
        <div class="main-content">
            <form class="formulario" id="form_emprestimo" action="registrar_emprestimo.php" method="post">

                <div class="form-section">
                    <div class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Informações do Livro
                    </div>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>ID do Livro</label>
                            <div class="input-wrapper">
                                <input type="number" name="cod_livro" required id="cod_livro" placeholder="Digite o ID do livro">
                                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40"><!--!Font Awesome Free v7.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path d="M192 576L512 576C529.7 576 544 561.7 544 544C544 526.3 529.7 512 512 512L512 445.3C530.6 438.7 544 420.9 544 400L544 112C544 85.5 522.5 64 496 64L448 64L448 233.4C448 245.9 437.9 256 425.4 256C419.4 256 413.6 253.6 409.4 249.4L368 208L326.6 249.4C322.4 253.6 316.6 256 310.6 256C298.1 256 288 245.9 288 233.4L288 64L192 64C139 64 96 107 96 160L96 480C96 533 139 576 192 576zM160 480C160 462.3 174.3 448 192 448L448 448L448 512L192 512C174.3 512 160 497.7 160 480z"/>
                                    </svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Nome do Livro</label>
                            <div class="input-wrapper">
                                <input type="text" name="titulo" required id="titulo" placeholder="Nome do livro" readonly>
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14,2 14,8 20,8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10,9 9,9 8,9"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Informações do Cliente
                    </div>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>ID do Cliente</label>
                            <div class="input-wrapper">
                                <input type="number" name="cod_cliente" required id="cod_cliente" placeholder="Digite o ID do cliente">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Nome do Cliente</label>
                            <div class="input-wrapper">
                                <input type="text" name="nome" required id="nome" placeholder="Nome do cliente" readonly>
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Datas do Empréstimo
                    </div>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>Data Empréstimo</label>
                            <div class="input-wrapper">
                                <input type="date" name="data_emprestimo" required id="data_emprestimo" min="1925-01-01" value="<?php echo $data_atual; ?>">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Data Devolução (Automática - 7 dias)</label>
                            <div class="input-wrapper">
                                <input type="date" name="data_devolucao" required id="data_devolucao" min="1925-01-01" value="<?php echo $data_devolucao_padrao; ?>" readonly style="background-color: #f8f9fa; color: #6c757d; cursor: not-allowed;">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                 
                <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="btnCadastrar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Cadastrar Empréstimo
                        </button>


                        <button type="reset" class="btn btn-secondary" onclick="document.getElementById('form_emprestimo').reset();">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                            Limpar Formulário
                        </button>
                    </div>
            </form>
        </div>
    </div>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
    <script src="subtelas_javascript/validaCadastro.js"></script>
    <script src="subtelas_javascript/buscarID.js"></script>

    <script>
        // Função para calcular automaticamente a data de devolução
        function calcularDataDevolucao() {
            const dataEmprestimo = document.getElementById('data_emprestimo');
            const campoDataDevolucao = document.getElementById('data_devolucao');
            
            if (dataEmprestimo && campoDataDevolucao && dataEmprestimo.value) {
                // Adiciona 7 dias à data de empréstimo
                const data = new Date(dataEmprestimo.value);
                data.setDate(data.getDate() + 7);
                
                // Formata a data para o formato YYYY-MM-DD
                const ano = data.getFullYear();
                const mes = String(data.getMonth() + 1).padStart(2, '0');
                const dia = String(data.getDate()).padStart(2, '0');
                const dataFormatada = `${ano}-${mes}-${dia}`;
                
                campoDataDevolucao.value = dataFormatada;
            }
        }

        // Adicionar evento quando a página carregar
        document.addEventListener('DOMContentLoaded', function() {
            const campoDataEmprestimo = document.getElementById('data_emprestimo');
            
            if (campoDataEmprestimo) {
                // Calcular data de devolução inicial
                setTimeout(calcularDataDevolucao, 100);
                
                // Calcular data de devolução sempre que a data de empréstimo mudar
                campoDataEmprestimo.addEventListener('change', calcularDataDevolucao);
                campoDataEmprestimo.addEventListener('input', calcularDataDevolucao);
            }
        });
    </script>
    
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
    </script>
</html>
