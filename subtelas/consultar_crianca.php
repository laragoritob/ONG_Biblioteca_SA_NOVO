<?php
session_start();
require_once '../conexao.php';

// Consulta todas as crianças
$sql = "SELECT 
          Cod_Crianca     AS ID Criança
          Nome            AS Nome
          Sexo            AS sexo
          Data_Nascimento AS data_nascimento
          Nome_Responsavel AS responsavel
          Telefone        AS telefone
        FROM crianca
        ORDER BY Nome ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $criancas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
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
      <h1>Consultar Crianças</h1>
  </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" placeholder="Buscar criança..." onkeyup="filtrarTabela()">
    </div>
  </div>
  
  <nav>
    <table id="criancas-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>NOME</th>
          <th>SEXO</th>
          <th>DATA DE NASCIMENTO</th>
          <th>NOME RESPONSÁVEL</th>
          <th>TELEFONE</th>
          <th>AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($criancas)): ?>
          <?php foreach ($criancas as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['ID Criança']) ?></td>
              <td><?= htmlspecialchars($c['nome']) ?></td>
              <td><?= htmlspecialchars($c['sexo']) ?></td>
              <td><?= date("d/m/Y", strtotime($c['data_nascimento'])) ?></td>
              <td><?= htmlspecialchars($c['responsavel']) ?></td>
              <td><?= htmlspecialchars($c['telefone']) ?></td>
              <td>
                <button onclick="editarCrianca(<?= $c['ID Criança'] ?>)">✏️ Editar</button>
                <button onclick="excluirCrianca(<?= $c['ID Criança'] ?>)">❌ Excluir</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Nenhuma criança encontrada</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </nav>

  <script src="subtelas_javascript/telconsultar_criancas.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
  <script>
    // Função para filtrar tabela pelo input de busca
    function filtrarTabela() {
      const input = document.getElementById("search-input").value.toLowerCase();
      const rows = document.querySelectorAll("#criancas-table tbody tr");
      
      rows.forEach(row => {
        const nome = row.cells[1].textContent.toLowerCase();
        row.style.display = nome.includes(input) ? "" : "none";
      });
    }
  </script>
    </div>
</body>
</html>
