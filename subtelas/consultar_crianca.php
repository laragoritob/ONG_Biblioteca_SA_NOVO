<?php
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
    exit();
  }

  // INICIALIZA VARIÁVEIS
  $clientes = [];
  $erro = null;

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA A CRIANÇA PELO ID OU NOME
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
          $busca = trim($_POST['busca']);
          
          // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
          if (is_numeric($busca)) {
              $sql = "SELECT Cod_Cliente, Nome, CPF, Email, Sexo, Nome_Responsavel, Telefone, Data_Nascimento, CEP, UF, Cidade, Bairro, Rua, Num_Residencia, Foto FROM cliente 
                        WHERE Cod_Cliente = :busca 
                        ORDER BY Cod_Cliente ASC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
          } else {
              $sql = "SELECT Cod_Cliente, Nome, CPF, Email, Sexo, Nome_Responsavel, Telefone, Data_Nascimento, CEP, UF, Cidade, Bairro, Rua, Num_Residencia, Foto FROM cliente 
                        WHERE Nome LIKE :busca_nome 
                        ORDER BY Cod_Cliente ASC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
          }
      } else {
          // BUSCA TODAS AS CRIANÇAS
          $sql = "SELECT Cod_Cliente, Nome, CPF, Email, Sexo, Nome_Responsavel, Telefone, Data_Nascimento, CEP, UF, Cidade, Bairro, Rua, Num_Residencia, Foto FROM cliente 
                    ORDER BY Cod_Cliente ASC";
          
          $stmt = $pdo->prepare($sql);
      }

      $stmt->execute();
      $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // GARANTIR QUE $clientes SEJA SEMPRE UM ARRAY
      if (!is_array($clientes)) {
          $clientes = [];
      }
      
  } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
      $clientes = [];
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
      <h1>Consultar Crianças</h1>
  </header>

  <form action="consultar_crianca.php" method="POST">
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
    <table id="funcionarios-table">
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
        <?php if (!empty($clientes) && is_array($clientes)): ?>
          <?php foreach ($clientes as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['Cod_Cliente']) ?></td>
              <td><?= htmlspecialchars($c['Nome']) ?></td>
              <td><?= htmlspecialchars($c['Sexo']) ?></td>
              <td><?= date("d/m/Y", strtotime($c['Data_Nascimento'])) ?></td>
              <td><?= htmlspecialchars($c['Nome_Responsavel']) ?></td>
              <td><?= htmlspecialchars($c['Telefone']) ?></td>
              <td>
                <a href="alterar_crianca.php?id=<?= htmlspecialchars($c['Cod_Cliente']) ?>" class="alterar">Alterar</a>
                |
                <a href="excluir_crianca.php?id=<?= htmlspecialchars($c['Cod_Cliente']) ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir esta criança?')">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Nenhuma criança encontrada</td></tr>
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
