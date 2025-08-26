<?php
    session_start();
    require_once "../conexao.php";

    // VERIFICA SE O USUÁRIO TEM PERMISSÃO
    if ($_SESSION['perfil'] != 1) {
        echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
        exit();
    }

    // INICIALIZA VARIÁVEIS
    $livros = [];
    $erro = null;

    try {
        // SE O FORMULÁRIO FOR ENVIADO, BUSCA O LIVRO PELO ID OU TÍTULO
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
            $busca = trim($_POST['busca']);
            
            // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME (TÍTULO)
            if (is_numeric($busca)) {
                $sql = "SELECT l.*, a.Nome_Autor, e.Nome_Editora, d.Nome_Doador 
                        FROM livro l 
                        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor 
                        LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora 
                        LEFT JOIN doador d ON l.Cod_Doador = d.Cod_Doador 
                        WHERE l.Cod_Livro = :busca 
                        ORDER BY l.Titulo ASC";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
            } else {
                $sql = "SELECT l.*, a.Nome_Autor, e.Nome_Editora, d.Nome_Doador 
                        FROM livro l 
                        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor 
                        LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora 
                        LEFT JOIN doador d ON l.Cod_Doador = d.Cod_Doador 
                        WHERE l.Titulo LIKE :busca_nome 
                        ORDER BY l.Titulo ASC";
                
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
            }
        } else {
            // BUSCA TODOS OS LIVROS
            $sql = "SELECT l.*, a.Nome_Autor, e.Nome_Editora, d.Nome_Doador 
                    FROM livro l 
                    LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor 
                    LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora 
                    LEFT JOIN doador d ON l.Cod_Doador = d.Cod_Doador 
                    ORDER BY l.Titulo ASC";
            
            $stmt = $pdo->prepare($sql);
        }

        $stmt->execute();
        $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // GARANTIR QUE $livros SEJA SEMPRE UM ARRAY
        if (!is_array($livros)) {
            $livros = [];
        }
        
    } catch (PDOException $e) {
        $erro = "Erro na consulta: " . $e->getMessage();
        $livros = [];
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Consultar Livros</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css">
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
    <header>
      <form action="../gerente.php" method="POST">
          <button class="btn-voltar">← Voltar</button>
      </form>
        <h1>Consultar Livros</h1>
    </header>

    <form action="consultar_livro.php" method="POST">
        <div id="search-container">
            <div class="input-wrapper">
                <span class="icon">🔎</span>
                <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou título..." required>
            </div>
        </div>
    </form>

    <?php if (isset($erro)) { ?>
        <div style="text-align: center; padding: 20px; color: #d32f2f; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 20px;">
            <p><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
        </div>
    <?php } ?>

  <nav>
    <?php if (!empty($livros) && is_array($livros)) { ?>
        <table id="funcionarios-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TÍTULO</th>
                    <th>AUTOR</th>
                    <th>EDITORA</th>
                    <th>DOADOR</th>
                    <th>DATA LANÇAMENTO</th>
                    <th>DATA REGISTRO</th>
                    <th>QUANTIDADE</th>
                    <th>PRATELEIRA</th>
                    <th>AÇÕES</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($livros as $livro) { ?>
                    <tr>
                        <td><?= htmlspecialchars($livro['Cod_Livro'] ?? '') ?></td>
                        <td><?= htmlspecialchars($livro['Titulo'] ?? '') ?></td>
                        <td><?= htmlspecialchars($livro['Nome_Autor'] ?? 'Não informado') ?></td>
                        <td><?= htmlspecialchars($livro['Nome_Editora'] ?? 'Não informado') ?></td>
                        <td><?= htmlspecialchars($livro['Nome_Doador'] ?? 'Não informado') ?></td>
                        <td><?= htmlspecialchars($livro['Data_Lancamento'] ?? '') ?></td>
                        <td><?= htmlspecialchars($livro['Data_Registro'] ?? '') ?></td>
                        <td><?= htmlspecialchars($livro['Quantidade'] ?? '0') ?></td>
                        <td><?= htmlspecialchars($livro['Num_Prateleira'] ?? '') ?></td>
                        <td>
                            <a href="alterar_livro.php?id=<?= htmlspecialchars($livro['Cod_Livro'] ?? '') ?>" class="alterar">Alterar</a>
                            |
                            <a href="excluir_livro.php?id=<?= htmlspecialchars($livro['Cod_Livro'] ?? '') ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir este livro?')">Excluir</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div style="text-align: center; padding: 20px; color: #666;">
            <p>Nenhum livro encontrado.</p>
        </div>
    <?php } ?>
    </nav>

    <script src="subtelas_javascript/consultas.js"></script>
    <script src="subtelas_javascript/sidebar.js"></script>
    </div>
</body>
</html>
