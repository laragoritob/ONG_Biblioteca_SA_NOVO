<?php
<<<<<<< Updated upstream
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1) {
    echo "<script>alert('Acesso Negado!'); window.location.href='../gerente.php';</script>";
    exit();
  }

  // INICIALIZA VARIÁVEIS
  $emprestimos = [];
  $erro = null;

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA O EMPRÉSTIMO PELO ID OU NOME DO CLIENTE
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
          $busca = trim($_POST['busca']);
          
          // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
          if (is_numeric($busca)) {
              $sql = "SELECT e.Cod_Emprestimo, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                             e.Data_Emprestimo, e.Data_Devolucao, e.Status
                        FROM emprestimo e 
                        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                        WHERE e.Cod_Emprestimo = :busca 
                        ORDER BY e.Data_Emprestimo DESC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
          } else {
              $sql = "SELECT e.Cod_Emprestimo, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                             e.Data_Emprestimo, e.Data_Devolucao, e.Status
                        FROM emprestimo e 
                        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                        WHERE c.Nome LIKE :busca_nome 
                        ORDER BY e.Data_Emprestimo DESC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
          }
      } else {
          // BUSCA TODOS OS EMPRÉSTIMOS
          $sql = "SELECT e.Cod_Emprestimo, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                         e.Data_Emprestimo, e.Data_Devolucao, e.Status
                    FROM emprestimo e 
                    INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                    INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                    ORDER BY e.Data_Emprestimo DESC";
          
          $stmt = $pdo->prepare($sql);
      }

      $stmt->execute();
      $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // GARANTIR QUE $emprestimos SEJA SEMPRE UM ARRAY
      if (!is_array($emprestimos)) {
          $emprestimos = [];
      }
      
  } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
      $emprestimos = [];
  }
=======
session_start();
require_once '../conexao.php';

//VERIFCA SE O USARIO TEM PERMISSAO DE adm OU secretaria
if($_SESSION['perfil'] !=1 && $_SESSION['perfil'] !=2){
    echo "<script>alert('Acesso negado!');windown.location.href='../gerente.php';</script>";
    exit();
}
$usuario= []; //INICIALIZA A VARIAVEL PARA EVITAR ERROS

//SE FORMULARIO FOR ENVIADO, BUSCA USUARIO PELO O ID OU NOME
if($_SERVER["REQUEST_METHOD"]== "POST" && !empty($_POST['busca'])){
    $busca= trim($_POST['busca']);

//VERIFICA SE A BUSCA É UM NUMERO OU UM NOME 
if(is_numeric($busca)){
    $sql= "SELECT * FROM emprestimo WHERE Cod_Emprestimo = :busca ORDER BY Cod_Emprestimo ASC";
    $stmt= $pdo-> prepare($sql);
    $stmt-> bindParam(':busca', $busca, PDO::PARAM_INT);
}else {
    $sql= "SELECT * FROM emprestimo WHERE Cod_Emprestimo LIKE :busca_nome ORDER BY nome ASC";
    $stmt= $pdo-> prepare($sql);
    $stmt-> bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
}
}else {
    $sql= "SELECT * FROM usuario ORDER BY nome ASC";
    $stmt= $pdo-> prepare($sql);
}

$stmt-> execute();
$usuarios= $stmt-> fetchAll(PDO::FETCH_ASSOC);
>>>>>>> Stashed changes
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="page-wrapper">
    <header>
      <form action="../gerente.php" method="POST">
        <button class="btn-voltar">← Voltar</button>
      </form>
      <h1>Consultar Empréstimos</h1>
    </header>

<<<<<<< Updated upstream
    <form action="consultar_emprestimo.php" method="POST">
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
      <table id="emprestimos-table">
=======
    <div id="search-container">
      <div class="input-wrapper">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
        <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
      </svg>
          <input type="text" id="search-input" name="nome_funcionario" placeholder="Buscar empréstimo..." required style="padding-left:40px;">
        </div>
    </div>

    <nav>
      <table id="funcionarios-table">
>>>>>>> Stashed changes
        <thead>
          <tr>
            <th>ID</th>
            <th>NOME DO CLIENTE</th>
            <th>NOME DO LIVRO</th>
            <th>DATA DO EMPRÉSTIMO</th>
            <th>DATA DE DEVOLUÇÃO</th>
<<<<<<< Updated upstream
            <th>STATUS</th>
=======
>>>>>>> Stashed changes
            <th>AÇÕES</th>
          </tr>
        </thead>
        <tbody>
<<<<<<< Updated upstream
          <?php if (!empty($emprestimos) && is_array($emprestimos)): ?>
            <?php foreach ($emprestimos as $e): ?>
              <tr>
                <td><?= htmlspecialchars($e['Cod_Emprestimo']) ?></td>
                <td><?= htmlspecialchars($e['Nome_Cliente']) ?></td>
                <td><?= htmlspecialchars($e['Nome_Livro']) ?></td>
                <td><?= date("d/m/Y", strtotime($e['Data_Emprestimo'])) ?></td>
                <td><?= $e['Data_Devolucao'] ? date("d/m/Y", strtotime($e['Data_Devolucao'])) : 'Não devolvido' ?></td>
                <td><?= htmlspecialchars($e['Status']) ?></td>
                <td>
                  <a href="alterar_emprestimo.php?id=<?= htmlspecialchars($e['Cod_Emprestimo']) ?>" class="alterar">Alterar</a>
                  |
                  <a href="excluir_emprestimo.php?id=<?= htmlspecialchars($e['Cod_Emprestimo']) ?>" class="excluir" onclick="return confirm('Tem certeza que deseja excluir este empréstimo?')">Excluir</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="7">Nenhum empréstimo encontrado</td></tr>
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
        const rows = document.querySelectorAll("#emprestimos-table tbody tr");
        
        rows.forEach(row => {
          const nome = row.cells[1].textContent.toLowerCase();
          row.style.display = nome.includes(input) ? "" : "none";
        });
      }
    </script>
    </div>
=======
          <!-- Dados da tabela serão carregados via JavaScript -->
        </tbody>
      </table>
    </nav>
  </div>

  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
>>>>>>> Stashed changes
</body>
</html>
