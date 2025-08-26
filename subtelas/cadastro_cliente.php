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
        $nome = $_POST['nome'];
        $perfil = $_POST['perfil'];
        $nome_responsavel = $_POST['nome_responsavel'];
        $cpf = $_POST['cpf'];
        $sexo = $_POST['sexo'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $data_nascimento = $_POST['data_nascimento'];
        $cep = $_POST['cep'];
        $uf = $_POST['uf'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $num_residencia = $_POST['num_residencia'];
        $foto = $_POST['foto'];

        $sql = "INSERT INTO cliente (cod_perfil, nome, nome_responsavel, cpf, sexo, email, telefone, data_nascimento, cep, uf, cidade, bairro, rua, num_residencia, foto) 
                    VALUES (:cod_perfil, :nome, :nome_responsavel, :cpf, :sexo, :email, :telefone, :data_nascimento, :cep, :uf, :cidade, :bairro, :rua, :num_residencia, :foto)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cod_perfil', $perfil);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':sexo', $sexo);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nascimento', $data_nascimento);
        $stmt->bindParam(':nome_responsavel', $nome_responsavel);
        $stmt->bindParam(':cep', $cep);
        $stmt->bindParam(':uf', $uf);
        $stmt->bindParam(':cidade', $cidade);
        $stmt->bindParam(':bairro', $bairro);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':num_residencia', $num_residencia);
        $stmt->bindParam(':foto', $foto);

        if ($stmt->execute()) {
            echo "<script>alert('Funcionário cadastrado com sucesso!');</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar funcionário!');</script>";
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            <button class="btn-voltar" onclick="window.history.back()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Voltar
            </button>
            <h1>Cadastro de Clientes</h1>
        </header>
        
        <main class="main-content">
            <div class="container">
                <form class="formulario" id="form_pessoal" action="cadastro_cliente.php" method="post" onsubmit="return validaFormulario()">
                    
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Informações Pessoais
                        </h2>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="nome">Nome Completo</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="nome" name="nome" required placeholder="Digite o nome completo">
                                </div>
                            </div>

                            

                            <div class="input-group">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <input type="date" id="data_nascimento" name="data_nascimento" required min="1925-01-01" max="" id="dataNascimento">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="sexo">Sexo</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    <select id="sexo" name="sexo" class="custom-select" required>
                                        <option value="" disabled selected>Selecione o sexo</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Masculino">Masculino</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="input-group">
                                <label for="cpf">CPF</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14,2 14,8 20,8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10,9 9,9 8,9"/>
                                    </svg>
                                    <input type="text" id="cpf" name="cpf" maxlength="14" oninput="formatCPF(this)" required placeholder="000.000.000-00">
                                </div>
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="email">E-mail</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <input type="email" id="email" name="email" required placeholder="exemplo@email.com">
                            </div>
                        </div>

                        <br>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="telefone">Telefone</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <input type="text" id="telefone" name="telefone" maxlength="15" oninput="formatTelefone(this)" required placeholder="(00) 00000-0000">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="foto">Foto do Funcionário</label>
                                <div class="file-upload-wrapper">
                                    <input type="text" name="seletor_arquivo" id="seletor_arquivo" readonly placeholder="Nenhum arquivo selecionado" class="file-display">
                                    <input type="file" id="foto" name="foto" accept=".png, .jpeg, .jpg" style="display: none;" multiple onchange="atualizarNomeArquivo()">
                                    <button type="button" class="file-select-btn" onclick="document.getElementById('foto').click()">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7,10 12,15 17,10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Selecionar Foto
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="responsavel">Nome do Responsável</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="nome_responsavel" name="nome_responsavel" required placeholder="Digite o nome do responsável">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="perfil">Tipo de Cliente</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                        <path d="M9 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                        <path d="M15 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                    </svg>
                                    <select id="perfil" name="perfil" class="custom-select" required>
                                        <option value="" disabled selected>Selecione o tipo</option>
                                        <option value="1">Criança</option>
                                        <option value="2">Responsável</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Endereço
                        </h2>
                        
                        <div class="form-row">
                            <div class="input-group">
                                <label for="cep">CEP</label>
                                <div class="input-wrapper cep-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="cep" name="cep" maxlength="9" oninput="formatCEP(this)" onblur="buscarCEP(this.value)" required placeholder="00000-000">
                                    <button type="button" class="btn-cep" onclick="buscarCEP(document.getElementById('cep').value)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="8"/>
                                            <path d="M21 21l-4.35-4.35"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="uf">Estado</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="uf" name="uf" required placeholder="Digite o estado">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cidade">Cidade</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="cidade" name="cidade" required placeholder="Digite a cidade">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="bairro">Bairro</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="bairro" name="bairro" required placeholder="Digite o bairro">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="rua">Rua</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="rua" name="rua" required placeholder="Digite a rua">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="num_residencia">Número</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="num_residencia" name="num_residencia" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required placeholder="0000">
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="btnCadastrar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Cadastrar Cliente
                        </button>
                        
                        <button type="button" class="btn btn-secondary" onclick="document.getElementById('form_pessoal').reset(); document.getElementById('arquivo').value = '';">
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
        </main>
    </div>
</body>
<script src="subtelas_javascript/validaCadastro.js"></script>
<script src="subtelas_javascript/sidebar.js"></script>
</html>