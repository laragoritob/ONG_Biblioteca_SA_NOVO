<?php
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
    exit();
  }

  // INICIALIZA VARIÁVEIS
  $multas = [];
  $erro = null;

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA A MULTA PELO ID OU NOME DO CLIENTE
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
          $busca = trim($_POST['busca']);
          
          // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
          if (is_numeric($busca)) {
              $sql = "SELECT m.Cod_Multa, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                             m.Data_Multa, m.Valor_Multa, m.Status
                        FROM multa m 
                        INNER JOIN emprestimo e ON m.Cod_Emprestimo = e.Cod_Emprestimo 
                        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                        WHERE m.Cod_Multa = :busca 
                        ORDER BY m.Data_Multa DESC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
          } else {
              $sql = "SELECT m.Cod_Multa, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                             m.Data_Multa, m.Valor_Multa, m.Status
                        FROM multa m 
                        INNER JOIN emprestimo e ON m.Cod_Emprestimo = e.Cod_Emprestimo 
                        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                        WHERE c.Nome LIKE :busca_nome 
                        ORDER BY m.Data_Multa DESC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
          }
      } else {
          // BUSCA TODAS AS MULTAS
          $sql = "SELECT m.Cod_Multa, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                         m.Data_Multa, m.Valor_Multa, m.Status
                    FROM multa m 
                    INNER JOIN emprestimo e ON m.Cod_Emprestimo = e.Cod_Emprestimo 
                    INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                    INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                    ORDER BY m.Data_Multa DESC";
          
          $stmt = $pdo->prepare($sql);
      }

      $stmt->execute();
      $multas = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // GARANTIR QUE $multas SEJA SEMPRE UM ARRAY
      if (!is_array($multas)) {
          $multas = [];
      }
      
  } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
      $multas = [];
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
    <div class="page-wrapper">
    <header>
        <form action="../gerente.php" method="POST">
            <button class="btn-voltar">← Voltar</button>
        </form>
        <h1>Consultar Multas</h1>
    </header>

    <form action="consultar_multa.php" method="POST">
      <div id="search-container">
        <div class="input-wrapper">
          <span class="icon">🔎</span>
          <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome do cliente..." onkeyup="filtrarTabela()">
        </div>
      </div>
    </form>
    
    <?php if (isset($erro)) { ?>
        <div style="text-align: center; padding: 20px; color: #d32f2f; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 20px;">
            <p><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
        </div>
    <?php } ?>
    
    <nav>
      <table id="multas-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>NOME DO CLIENTE</th>
            <th>NOME DO LIVRO</th>
            <th>DATA DA MULTA</th>
            <th>VALOR DA MULTA</th>
            <th>STATUS</th>
            <th>AÇÕES</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($multas) && is_array($multas)): ?>
            <?php foreach ($multas as $m): ?>
              <tr>
                <td><?= htmlspecialchars($m['Cod_Multa']) ?></td>
                <td><?= htmlspecialchars($m['Nome_Cliente']) ?></td>
                <td><?= htmlspecialchars($m['Nome_Livro']) ?></td>
                <td><?= date("d/m/Y", strtotime($m['Data_Multa'])) ?></td>
                <td>R$ <?= number_format($m['Valor_Multa'], 2, ',', '.') ?></td>
                <td><?= htmlspecialchars($m['Status']) ?></td>
                <td>
                  <a href="alterar_multa.php?id=<?= htmlspecialchars($m['Cod_Multa']) ?>" class="alterar">Alterar</a>
                  |
                  <a href="excluir_multa.php?id=<?= htmlspecialchars($m['Cod_Multa']) ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir esta multa?')">Excluir</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="7">Nenhuma multa encontrada</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </nav>

    <script src="subtelas_javascript/consultas.js"></script>
    <script src="subtelas_javascript/sidebar.js"></script>
    <script>
      // Função para filtrar tabela pelo input de busca
      function filtrarTabela() {
        const input = document.getElementById("search-input").value.toLowerCase();
        const rows = document.querySelectorAll("#multas-table tbody tr");
        
        rows.forEach(row => {
          const nome = row.cells[1].textContent.toLowerCase();
          row.style.display = nome.includes(input) ? "" : "none";
        });
      }
    </script>
    </div>
</body>
</html>
