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
  $filtro_perfil = isset($_POST['filtro_perfil']) ? $_POST['filtro_perfil'] : '';

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA O CLIENTE PELO ID, NOME OU PERFIL
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $busca = isset($_POST['busca']) ? trim($_POST['busca']) : '';
          $filtro_perfil = isset($_POST['filtro_perfil']) ? $_POST['filtro_perfil'] : '';
          
          // CONSTRÓI A CONSULTA SQL BASE
          $sql = "SELECT c.Cod_Cliente, c.Nome, c.CPF, c.Email, c.Sexo, c.Nome_Responsavel, c.Telefone, c.Data_Nascimento, c.CEP, c.UF, c.Cidade, c.Bairro, c.Rua, c.Num_Residencia, c.Foto, pc.Nome_Perfil 
                    FROM cliente c 
                    LEFT JOIN perfil_cliente pc ON c.Cod_Perfil = pc.Cod_Perfil 
                    WHERE 1=1";
          
          $params = [];
          
          // ADICIONA FILTRO POR PERFIL SE SELECIONADO
          if (!empty($filtro_perfil)) {
              $sql .= " AND c.Cod_Perfil = :filtro_perfil";
              $params[':filtro_perfil'] = $filtro_perfil;
          }
          
          // ADICIONA FILTRO POR BUSCA SE FORNECIDA
          if (!empty($busca)) {
              if (is_numeric($busca)) {
                  $sql .= " AND c.Cod_Cliente = :busca";
                  $params[':busca'] = $busca;
              } else {
                  $sql .= " AND c.Nome LIKE :busca_nome";
                  $params[':busca_nome'] = "$busca%";
              }
          }
          
          $sql .= " ORDER BY c.Cod_Cliente ASC";
          
          $stmt = $pdo->prepare($sql);
          
          // BINDA OS PARÂMETROS
          foreach ($params as $key => $value) {
              if (is_numeric($value)) {
                  $stmt->bindValue($key, $value, PDO::PARAM_INT);
              } else {
                  $stmt->bindValue($key, $value, PDO::PARAM_STR);
              }
          }
      } else {
          // BUSCA TODOS OS CLIENTES
          $sql = "SELECT c.Cod_Cliente, c.Nome, c.CPF, c.Email, c.Sexo, c.Nome_Responsavel, c.Telefone, c.Data_Nascimento, c.CEP, c.UF, c.Cidade, c.Bairro, c.Rua, c.Num_Residencia, c.Foto, pc.Nome_Perfil 
                    FROM cliente c 
                    LEFT JOIN perfil_cliente pc ON c.Cod_Perfil = pc.Cod_Perfil 
                    ORDER BY c.Cod_Cliente ASC";
          
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
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .filtro-container {
      display: flex;
      gap: 15px;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    
    #search-container {
      display: flex;
      align-items: center;
      gap: 15px;
      flex: 1;
      min-width: 300px;
    }
    
    .input-wrapper {
      flex: 1;
      position: relative;
    }
    
    .filtro-select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background: white;
      min-width: 150px;
      font-size: 14px;
      color: #333;
      cursor: pointer;
      transition: border-color 0.3s;
    }
    
    .filtro-select:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
    }
    
    .btn-filtrar {
      padding: 10px 20px;
      background:rgb(83, 86, 238);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
      font-weight: 500;
    }
    
    .btn-filtrar:hover {
      background:rgb(53, 69, 211);
    }
    
    .btn-limpar {
      padding: 10px 20px;
      background: #e53e3e;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
      font-weight: 500;
    }
    
    .btn-limpar:hover {
      background: #c53030;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
      .filtro-container {
        flex-direction: column;
        align-items: stretch;
      }
      
      #search-container {
        flex-direction: column;
        min-width: auto;
      }
      
      .input-wrapper {
        width: 100%;
      }
      
      .filtro-select {
        min-width: auto;
        width: 100%;
      }
    }
  </style>
</head>

<body>
    <div class="page-wrapper">
  <header>
              <form action="../gerente.php" method="POST">
            <button class="btn-voltar">← Voltar</button>
        </form>
      <h1>Consultar Clientes</h1>
  </header>

    <div class="filtro-container">
      <div id="search-container">
        <div class="input-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
          <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
        </svg>
          <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>" onkeyup="filtrarTabela()">
        </div>
        
        <select name="filtro_perfil" class="filtro-select">
          <option value="">Todos os perfis</option>
          <option value="1" <?= $filtro_perfil == '1' ? 'selected' : '' ?>>Criança</option>
          <option value="2" <?= $filtro_perfil == '2' ? 'selected' : '' ?>>Responsável</option>
        </select>
        
        <button type="submit" class="btn-filtrar">Filtrar</button>
        <button type="button" class="btn-limpar" onclick="limparFiltros()">Limpar Filtros</button>
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
          <th>PERFIL</th>
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
                  <td><?= htmlspecialchars($c['Nome_Perfil'] ?? 'Não definido') ?></td>
                  <td><?= htmlspecialchars($c['Sexo']) ?></td>
                  <td><?= date("d/m/Y", strtotime($c['Data_Nascimento'])) ?></td>
                  <td><?= htmlspecialchars($c['Nome_Responsavel']) ?></td>
                  <td><?= htmlspecialchars($c['Telefone']) ?></td>
                  <td>
                    <button onclick="editarCliente(<?= $c['Cod_Cliente'] ?>)">✏️</button>
                    <button onclick="excluirCliente(<?= $c['Cod_Cliente'] ?>)">🗑️</button>
                  </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">Nenhum cliente encontrado.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </nav>

  <script src="subtelas_javascript/consultas.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
  <script src="subtelas_javascript/telconsultar_clientes.js"></script>
  <script>
    // Função para filtrar tabela pelo input de busca
    function filtrarTabela() {
      const input = document.getElementById("search-input").value.toLowerCase();
      const rows = document.querySelectorAll("#funcionarios-table tbody tr");
      
      rows.forEach(row => {
        const nome = row.cells[1].textContent.toLowerCase();
        row.style.display = nome.includes(input) ? "" : "none";
      });
    }
    
    // Função para limpar filtros
    function limparFiltros() {
      document.getElementById("search-input").value = "";
      document.querySelector("select[name='filtro_perfil']").value = "";
      window.location.href = "consultar_cliente.php";
    }
  </script>
    </div>
</body>
</html>
