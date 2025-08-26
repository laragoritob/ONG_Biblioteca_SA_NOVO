<?php
    session_start();
    require_once '../conexao.php';

    // VERIFICA SE O USUÁRIO TEM PERMISSÃO
    // SUPONDO QUE O PERFIL 1 SEJA O ADMINISTRADOR
    if ($_SESSION['perfil'] != 1) {
        echo "<script>alert('Acesso Negado!');window.location.href='../gerente.php';</script>";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $cod_livro = $_POST['cod_livro'];
        $cod_cliente = $_POST['cod_cliente'];
        $data_emprestimo = $_POST['data_emprestimo'];
        $data_devolucao = $_POST['data_devolucao'];

        $sql = "INSERT INTO emprestimo (cod_livro,cod_cliente,data_emprestimo,data_devolucao) 
                    VALUES (:cod_livro,:cod_cliente,:data_emprestimo,:data_devolucao)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cod_livro', $cod_livro);
        $stmt->bindParam(':cod_cliente', $cod_cliente);
        $stmt->bindParam(':data_emprestimo', $data_emprestimo);
        $stmt->bindParam(':data_devolucao', $data_devolucao);

        if ($stmt->execute()) {
            echo "<script>alert('Empréstimo cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar empréstimo!');</script>";
        }
    }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">

</head>
<body>
    <!-- Botão para abrir sidebar -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Sidebar Lateral -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>ONG Biblioteca</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="../gerente.php">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                    </svg>
                    Dashboard
                </a></li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Cliente
                    </a>
                    <div class="dropdown-menu">
                        <a href="cadastro_cliente.php">Cadastrar Cliente</a>
                        <a href="consultar_crianca.php">Consultar Criança</a>
                        <a href="consultar_responsavel.php">Consultar Responsável</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Funcionário
                    </a>
                    <div class="dropdown-menu">
                        <a href="cadastro_funcionario.php">Cadastrar Funcionário</a>
                        <a href="consultar_funcionario.php">Consultar Funcionário</a>
                        <a href="relatorio_funcionario.php">Relatório Funcionário</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Livro
                    </a>
                    <div class="dropdown-menu">
                        <a href="registrar_livro.php">Registrar Livro</a>
                        <a href="consultarlivro.php">Consultar Livro</a>
                        <a href="controleestoque.php">Controle de Estoque</a>
                        <a href="catalogar_livro.php">Catalogar Livro</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Empréstimo
                    </a>
                    <div class="dropdown-menu">
                        <a href="registrar_emprestimo.php">Registrar Empréstimo</a>
                        <a href="consultar_emprestimo.php">Consultar Empréstimo</a>
                        <a href="consultar_multa.php">Consultar Multa</a>
                        <a href="devolver_livro.php">Devolver Livro</a>
                        <a href="renovar_emprestimo.php">Renovar Empréstimo</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Doador
                    </a>
                    <div class="dropdown-menu">
                        <a href="registrar_doador.php">Registrar Doador</a>
                        <a href="consultar_doador.php">Consultar Doador</a>
                        <a href="historico_doacoes.php">Histórico de Doações</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <p>© 2025 ONG Biblioteca</p>
        </div>
    </div>

    <!-- Overlay para fechar sidebar -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="page-wrapper">
        <header class="header">
            <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
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
                                <input type="date" name="data_emprestimo" required id="data_emprestimo" min="1925-01-01">
                                <svg class="input-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Data Devolução</label>
                            <div class="input-wrapper">
                                <input type="date" name="data_devolucao" required id="data_devolucao" min="1925-01-01">
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
                            Cadastrar Livro
                        </button>


                        <button type="reset" class="btn btn-secondary" onclick="document.getElementById('form_pessoal').reset(); document.getElementById('arquivo').value = '';">
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
</body>
    <script src="subtelas_javascript/validaCadastro.js"></script>
    <script src="subtelas_javascript/buscarID.js"></script>
    <script src="subtelas_javascript/sidebar.js"></script>
</html>
