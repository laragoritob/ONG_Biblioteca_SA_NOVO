<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Apenas Gerente (perfil 1) e Bibliotecário (perfil 3) podem consultar editoras
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
    // Se não tem permissão, exibe alerta e redireciona para login
    echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
    exit();
}

// Define qual página o usuário deve retornar baseado em seu perfil
switch ($_SESSION['perfil']) {
    case 1: // Gerente - pode acessar todas as funcionalidades
        $linkVoltar = "../gerente.php";
        break;
    case 2: // Gestor - não tem acesso a esta página, mas mantido para consistência
        $linkVoltar = "../gestor.php";
        break;
    case 3: // Bibliotecário - pode consultar editoras
        $linkVoltar = "../bibliotecario.php";
        break;
    case 4: // Recreador - não tem acesso a esta página
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

  // INICIALIZA VARIÁVEIS
  $editoras = [];
  $erro = null;
  $mostrar_inativos = isset($_GET['inativos']) && $_GET['inativos'] === 'true';

// Processa reativação se solicitada
if (isset($_GET['reativar']) && is_numeric($_GET['reativar'])) {
    $id_reativar = intval($_GET['reativar']);
    try {
        $sql_reativar = "UPDATE editora SET status = 'ativo' WHERE Cod_Editora = :id";
        $stmt_reativar = $pdo->prepare($sql_reativar);
        $stmt_reativar->bindParam(':id', $id_reativar, PDO::PARAM_INT);
        
        if ($stmt_reativar->execute()) {
            $sucesso_reativar = "Editora reativada com sucesso!";
        } else {
            $erro_reativar = "Erro ao reativar editora.";
        }
    } catch (PDOException $e) {
        $erro_reativar = "Erro ao reativar editora: " . $e->getMessage();
    }
}

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA A EDITORA PELO ID OU NOME
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $busca = isset($_POST['busca']) ? trim($_POST['busca']) : '';
          
          // CONSTRÓI A CONSULTA SQL BASE
          $status_condicao = $mostrar_inativos ? "status = 'inativo'" : "status = 'ativo'";
          $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE $status_condicao ORDER BY Cod_Editora ASC";
          
          $params = [];
          
                     // ADICIONA FILTRO POR BUSCA SE FORNECIDA
           if (!empty($busca)) {
               if (is_numeric($busca)) {
                   $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE Cod_Editora = :busca AND status = 'ativo' ORDER BY Cod_Editora ASC";
                   $params[':busca'] = $busca;
               } else {
                   $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE Nome_Editora LIKE :busca_nome AND status = 'ativo' ORDER BY Cod_Editora ASC";
                   $params[':busca_nome'] = "$busca%";
               }
           }
           
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
          // BUSCA TODAS AS EDITORAS
          $status_condicao = $mostrar_inativos ? "status = 'inativo'" : "status = 'ativo'";
          $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE $status_condicao ORDER BY Cod_Editora ASC";
          $stmt = $pdo->prepare($sql);
      }

             $stmt->execute();
       $editoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
       // GARANTIR QUE $editoras SEJA SEMPRE UM ARRAY
       if (!is_array($editoras)) {
           $editoras = [];
       }
      
  } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
      $editoras = [];
  }
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
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
    
    .status-buttons {
      display: flex;
      gap: 10px;
      align-items: center;
      margin-top: 15px;
    }
    
    .btn-status {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 16px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-left: 270px;
    }
    
    .btn-inativos {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: white;
    }
    
    .btn-inativos:hover {
      background: linear-gradient(135deg, #d97706, #b45309);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-ativos {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
    }
    
    .btn-ativos:hover {
      background: linear-gradient(135deg, #059669, #047857);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .btn-reactivate {
      background: linear-gradient(135deg, #10b981, #059669) !important;
      color: white !important;
    }
    
    .btn-reactivate:hover {
      background: linear-gradient(135deg, #059669, #047857) !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    a {
      text-decoration: none;
    }
    
    .nome-clicavel {
      cursor: pointer;
      color: #667eea;
      text-decoration: none;
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
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="page-wrapper">
    <header>
        <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
        <h1>Consultar Editoras</h1>
    </header>

      <form method="POST" action="consultar_editora.php">
      <div class="filtro-container">
       <div id="search-container">
          <div class="input-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
            <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
          </svg>
            <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome da editora..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>">
          </div>
          
          <button type="submit" class="btn-filtrar">Buscar</button>
          
          <div class="status-buttons">
            <?php if (!$mostrar_inativos): ?>
              <a href="consultar_editora.php?inativos=true" class="btn-status btn-inativos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                </svg>
                Ver Inativas
              </a>
            <?php else: ?>
              <a href="consultar_editora.php" class="btn-status btn-ativos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                </svg>
                Ver Ativas
              </a>
            <?php endif; ?>
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
          <th>NOME DA EDITORA</th>
          <th>TELEFONE</th>
          <th>E-MAIL</th>
          <th>AÇÕES</th>
        </tr>
      </thead>
             <tbody>
         <?php if (empty($editoras)) { ?>
           <tr>
             <td colspan="5" class="empty-state">
               <div class="empty-content">
                 <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                   <circle cx="11" cy="11" r="8"/>
                   <path d="m21 21-4.35-4.35"/>
                 </svg>
                 <h3>Nenhuma editora encontrada</h3>
                 <p>Tente ajustar os filtros de busca ou adicione uma nova editora.</p>
               </div>
             </td>
           </tr>
         <?php } else { ?>
           <?php foreach ($editoras as $editora) { ?>
             <tr>
               <td data-label="ID"><?= htmlspecialchars($editora['Cod_Editora']) ?></td>
               <td data-label="Nome da Editora"><?= htmlspecialchars($editora['Nome_Editora']) ?></td>
               <td data-label="Telefone"><?= htmlspecialchars($editora['Telefone']) ?></td>
               <td data-label="E-mail"><?= htmlspecialchars($editora['Email']) ?></td>
               <td data-label="Ações" class="action-buttons">
                 <?php if ($mostrar_inativos): ?>
                   <a href="consultar_editora.php?reativar=<?= $editora['Cod_Editora'] ?>&inativos=true" class="btn-action btn-reactivate" title="Reativar" onclick="return confirm('Deseja reativar esta editora?')">
                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                       <path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                     </svg>
                   </a>
                 <?php else: ?>
                   <a href="alterar_editora.php?id=<?= $editora['Cod_Editora'] ?>" class="btn-action btn-edit" title="Alterar">
                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                       <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                       <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                     </svg>
                   </a>
                   <a href="excluir_editora.php?id=<?= $editora['Cod_Editora'] ?>" class="btn-action btn-delete" title="Inativar">
                     <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                       <path d="M3 6h18"/>
                       <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                       <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                     </svg>
                   </a>
                 <?php endif; ?>
               </td>
             </tr>
           <?php } ?>
         <?php } ?>
       </tbody>
    </table>
  </nav>

  <script>
    function limparFiltros() {
      document.getElementById('search-input').value = '';
      window.location.href = 'consultar_editora.php';
    }
  </script>
  
  <!-- Notificações de reativação -->
  <?php if (isset($sucesso_reativar)): ?>
  <script>
      Swal.fire({
          icon: 'success',
          title: 'Sucesso!',
          text: '<?= addslashes($sucesso_reativar) ?>',
          confirmButtonText: 'OK'
      }).then(() => {
          window.location.href = 'consultar_editora.php';
      });
  </script>
  <?php endif; ?>
  
  <?php if (isset($erro_reativar)): ?>
  <script>
      Swal.fire({
          icon: 'error',
          title: 'Erro!',
          text: '<?= addslashes($erro_reativar) ?>',
          confirmButtonText: 'OK'
      });
  </script>
  <?php endif; ?>

    </div>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>