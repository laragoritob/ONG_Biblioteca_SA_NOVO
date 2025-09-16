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

    try {
        // Inicia transação para garantir consistência dos dados
        $pdo->beginTransaction();
        
        // Primeiro, verifica se há estoque disponível
        $sql_estoque = "SELECT Quantidade, Titulo FROM livro WHERE Cod_Livro = :cod_livro AND status = 'ativo'";
        $stmt_estoque = $pdo->prepare($sql_estoque);
        $stmt_estoque->bindParam(':cod_livro', $cod_livro);
        $stmt_estoque->execute();
        $livro = $stmt_estoque->fetch(PDO::FETCH_ASSOC);
        
        if (!$livro) {
            throw new Exception("Livro não encontrado ou inativo!");
        }
        
        if ($livro['Quantidade'] <= 0) {
            throw new Exception("Estoque insuficiente! Não há exemplares disponíveis do livro '{$livro['Titulo']}'.");
        }
        
        // Query SQL para inserir o novo empréstimo no banco de dados
        $sql = "INSERT INTO emprestimo (cod_livro,cod_cliente,data_emprestimo,data_devolucao) 
                    VALUES (:cod_livro,:cod_cliente,:data_emprestimo,:data_devolucao)";

        // Prepara a query usando prepared statement para segurança
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cod_livro', $cod_livro);
        $stmt->bindParam(':cod_cliente', $cod_cliente);
        $stmt->bindParam(':data_emprestimo', $data_emprestimo);
        $stmt->bindParam(':data_devolucao', $data_devolucao);

        // Executa a inserção do empréstimo
        if (!$stmt->execute()) {
            throw new Exception("Erro ao cadastrar empréstimo!");
        }
        
        // Decrementa o estoque do livro
        $nova_quantidade = $livro['Quantidade'] - 1;
        $sql_update_estoque = "UPDATE livro SET Quantidade = :quantidade WHERE Cod_Livro = :cod_livro";
        $stmt_update = $pdo->prepare($sql_update_estoque);
        $stmt_update->bindParam(':quantidade', $nova_quantidade);
        $stmt_update->bindParam(':cod_livro', $cod_livro);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Erro ao atualizar estoque!");
        }
        
        // Confirma a transação
        $pdo->commit();
        
        // Verifica se precisa emitir alerta de estoque baixo
        if ($nova_quantidade < 5) {
            $alerta_estoque = "⚠️ ALERTA: Estoque baixo! O livro '{$livro['Titulo']}' possui apenas {$nova_quantidade} exemplar(es) restante(s).";
        }
        
        // Define mensagem de sucesso
        $sucesso = "Empréstimo cadastrado com sucesso!";
        
    } catch (Exception $e) {
        // Em caso de erro, desfaz a transação
        $pdo->rollBack();
        $erro = $e->getMessage();
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
    <style>
    /* Estilos para autocomplete */
        .autocomplete-container {
            position: relative;
            width: 100%;
        }
        
        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 0.5rem 0.5rem;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: none;
        }
        
        .autocomplete-suggestion {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }
        
        .autocomplete-suggestion:hover {
            background-color:rgb(233, 233, 233);
        }
        
        .autocomplete-suggestion:last-child {
            border-bottom: none;
        }
        
        .autocomplete-suggestion.highlighted {
            background-color: #6366f1;
            color: white;
        }
        
        .input-wrapper {
            position: relative;
        }
    </style>
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
            <h1>Fazer Empréstimo</h1>
        </header>
        
        <div class="main-content">
            <form class="formulario" id="form_emprestimo" action="registrar_emprestimo.php" method="post">
                        <div class="input-group">
                            <div class="input-wrapper">
                                <input type="hidden" name="cod_livro" required id="cod_livro" placeholder="Digite o ID do livro">
                            </div>
                        </div>

                               
                        <div class="input-group">
                            <div class="input-wrapper">
                                <input type="hidden" name="cod_cliente" required id="cod_cliente" placeholder="Digite o ID do cliente">
                            </div>
                        </div>

                <div class="form-section">
                    <h2 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Informações de Empréstimo
</h2>

                    <div class="form-row">
                        <div class="input-group">
                            <label>Nome do Livro</label>
                            <div class="autocomplete-container">
                                <div class="input-wrapper">
                                    <input type="text" name="titulo" required id="titulo" placeholder="Nome do livro" autocomplete="off">
                                    <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14,2 14,8 20,8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10,9 9,9 8,9"></polyline>
                                    </svg>
                                </div>
                                <div class="autocomplete-suggestions" id="livro-suggestions"></div>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Nome do Cliente</label>
                            <div class="autocomplete-container">
                                <div class="input-wrapper">
                                    <input type="text" name="nome" required id="nome" placeholder="Nome do cliente" autocomplete="off">
                                    <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                </div>
                                <div class="autocomplete-suggestions" id="cliente-suggestions"></div>
                            </div>
                        </div>
</div>
                
<br>

                <div class="form-section">
                    <h2 class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Datas do Empréstimo
</h2>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>Data Empréstimo</label>
                            <div class="input-wrapper">
                                <input type="date" name="data_emprestimo" required id="data_emprestimo" min="" max="" readonly value="<?php echo $data_atual; ?>">
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
    <script src="subtelas_javascript/validaEmprestimo.js"></script>
    <script src="subtelas_javascript/buscarID.js"></script>

    <script>
    // Autocomplete genérico inspirado no registrar_livro
    function setupAutocomplete(inputId, suggestionsId, url, onSelect) {
        const input = document.getElementById(inputId);
        const box = document.getElementById(suggestionsId);
        if (!input || !box) return;

        let highlightedIndex = -1;
        let items = [];

        function render(list) {
            items = list || [];
            box.innerHTML = '';
            highlightedIndex = -1;
            if (!items.length) { box.style.display = 'none'; return; }
            items.forEach((item, idx) => {
                const div = document.createElement('div');
                div.className = 'autocomplete-suggestion';
                div.textContent = item.label;
                div.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    onSelect(item);
                    box.style.display = 'none';
                });
                box.appendChild(div);
            });
            box.style.display = 'block';
        }

        function fetchData(q) {
            if (!q || q.trim().length < 2) { render([]); return; }
            fetch(url + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    const list = (data || []).map(row => ({
                        id: row.cod_livro || row.cod_cliente || row.id || row.cod,
                        label: row.titulo || row.nome || row.label
                    }));
                    render(list);
                })
                .catch(() => render([]));
        }

        const debounce = (fn, d) => { let t; return v => { clearTimeout(t); t = setTimeout(() => fn(v), d); }; };
        const debouncedFetch = debounce(fetchData, 250);

        input.addEventListener('input', () => debouncedFetch(input.value));
        input.addEventListener('focus', () => { if (items.length) box.style.display = 'block'; });
        input.addEventListener('blur', () => setTimeout(() => { box.style.display = 'none'; }, 150));

        input.addEventListener('keydown', function(e) {
            const children = Array.from(box.children);
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                highlightedIndex = Math.min(highlightedIndex + 1, children.length - 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                highlightedIndex = Math.max(highlightedIndex - 1, 0);
            } else if (e.key === 'Enter') {
                if (highlightedIndex >= 0 && highlightedIndex < items.length) {
                    e.preventDefault();
                    onSelect(items[highlightedIndex]);
                    box.style.display = 'none';
                }
            } else {
                return;
            }
            children.forEach((c, i) => c.classList.toggle('highlighted', i === highlightedIndex));
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Livro pelo título
        setupAutocomplete('titulo', 'livro-suggestions', 'buscar_livros.php?termo=', function(item) {
            const input = document.getElementById('titulo');
            const hidden = document.getElementById('cod_livro');
            input.value = item.label;
            if (hidden) hidden.value = item.id;
        });

        // Cliente pelo nome
        setupAutocomplete('nome', 'cliente-suggestions', 'buscar_clientes.php?termo=', function(item) {
            const input = document.getElementById('nome');
            const hidden = document.getElementById('cod_cliente');
            input.value = item.label;
            if (hidden) hidden.value = item.id;
        });
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
                }).then(() => {
                    <?php if (isset($alerta_estoque)): ?>
                        // Mostra alerta de estoque baixo após o sucesso
                        Swal.fire({
                            title: 'Alerta de Estoque',
                            text: '<?= addslashes($alerta_estoque) ?>',
                            icon: 'warning',
                            confirmButtonText: 'Entendi',
                            confirmButtonColor: '#ff9800',
                            customClass: {
                                title: 'swal2-title-arial',
                                confirmButton: 'swal2-confirm'
                            }
                        });
                    <?php endif; ?>
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
