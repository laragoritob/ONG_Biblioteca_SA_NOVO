<?php
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
    exit();
  }

  // INICIALIZA VARIÁVEIS
  $responsaveis = [];
  $erro = null;

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA O RESPONSÁVEL PELO ID OU NOME
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
          $busca = trim($_POST['busca']);
          
          // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
          if (is_numeric($busca)) {
              $sql = "SELECT DISTINCT r.Cod_Responsavel, r.Nome_Responsavel, r.Email, r.Telefone, r.Data_Nascimento 
                        FROM responsavel r 
                        INNER JOIN cliente c ON r.Cod_Responsavel = c.Cod_Responsavel 
                        WHERE r.Cod_Responsavel = :busca 
                        ORDER BY r.Nome_Responsavel ASC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
          } else {
              $sql = "SELECT DISTINCT r.Cod_Responsavel, r.Nome_Responsavel, r.Email, r.Telefone, r.Data_Nascimento 
                        FROM responsavel r 
                        INNER JOIN cliente c ON r.Cod_Responsavel = c.Cod_Responsavel 
                        WHERE r.Nome_Responsavel LIKE :busca_nome 
                        ORDER BY r.Nome_Responsavel ASC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
          }
      } else {
          // BUSCA TODOS OS RESPONSÁVEIS
          $sql = "SELECT DISTINCT r.Cod_Responsavel, r.Nome_Responsavel, r.Email, r.Telefone, r.Data_Nascimento 
                    FROM responsavel r 
                    INNER JOIN cliente c ON r.Cod_Responsavel = c.Cod_Responsavel 
                    ORDER BY r.Nome_Responsavel ASC";
          
          $stmt = $pdo->prepare($sql);
      }

      $stmt->execute();
      $responsaveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // GARANTIR QUE $responsaveis SEJA SEMPRE UM ARRAY
      if (!is_array($responsaveis)) {
          $responsaveis = [];
      }
      
  } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
      $responsaveis = [];
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
        <h1>Consultar Responsáveis</h1>
    </header>

    <form action="consultar_responsavel.php" method="POST">
      <div id="search-container">
        <div class="input-wrapper">
          <span class="icon">🔎</span>
          <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome..." onkeyup="filtrarTabela()">
        </div>
      </div>
    </form>
    
    <?php if (isset($erro)) { ?>
        <div style="text-align: center; padding: 20px; color: #d32f2f; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 20px;">
            <p><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
        </div>
    <?php } ?>
    
    <nav>
      <table id="responsaveis-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>NOME DO RESPONSÁVEL</th>
            <th>E-MAIL</th>
            <th>TELEFONE</th>
            <th>DATA NASCIMENTO</th>
            <th>AÇÕES</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($responsaveis) && is_array($responsaveis)): ?>
            <?php foreach ($responsaveis as $r): ?>
              <tr>
                <td><?= htmlspecialchars($r['Cod_Responsavel']) ?></td>
                <td><?= htmlspecialchars($r['Nome_Responsavel']) ?></td>
                <td><?= htmlspecialchars($r['Email']) ?></td>
                <td><?= htmlspecialchars($r['Telefone']) ?></td>
                <td><?= date("d/m/Y", strtotime($r['Data_Nascimento'])) ?></td>
                <td>
                  <a href="alterar_responsavel.php?id=<?= htmlspecialchars($r['Cod_Responsavel']) ?>" class="alterar">Alterar</a>
                  |
                  <a href="excluir_responsavel.php?id=<?= htmlspecialchars($r['Cod_Responsavel']) ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir este responsável?')">Excluir</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="6">Nenhum responsável encontrado</td></tr>
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
        const rows = document.querySelectorAll("#responsaveis-table tbody tr");
        
        rows.forEach(row => {
          const nome = row.cells[1].textContent.toLowerCase();
          row.style.display = nome.includes(input) ? "" : "none";
        });
      }
    </script>
    </div>
</body>
</html>