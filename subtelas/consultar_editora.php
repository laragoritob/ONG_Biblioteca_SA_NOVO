<?php
  session_start();
  require_once '../conexao.php';

  if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
        echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
        exit();
    }

    // Determina a página de "voltar" dependendo do perfil do usuário
    switch ($_SESSION['perfil']) {
        case 1: // Gerente
            $linkVoltar = "../gerente.php";
            break;
        case 2: // Gestor
            $linkVoltar = "../gestor.php";
            break;
        case 3: // Bibliotecário
            $linkVoltar = "../bibliotecario.php";
            break;
        case 4: // Recreador
            $linkVoltar = "../recreador.php";
            break;
        case 5: // Repositor
            $linkVoltar = "../repositor.php";
            break;
        default:
            // PERFIL NÃO RECONHECIDO, REDIRECIONA PARA LOGIN
            $linkVoltar = "../index.php";
            break;
    }

  // INICIALIZA VARIÁVEIS
  $editoras = [];
  $erro = null;

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA A EDITORA PELO ID OU NOME
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $busca = isset($_POST['busca']) ? trim($_POST['busca']) : '';
          
          // CONSTRÓI A CONSULTA SQL BASE
          $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE 1=1 ORDER BY Cod_Editora ASC";
          
          $params = [];
          
                     // ADICIONA FILTRO POR BUSCA SE FORNECIDA
           if (!empty($busca)) {
               if (is_numeric($busca)) {
                   $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE Cod_Editora = :busca ORDER BY Cod_Editora ASC";
                   $params[':busca'] = $busca;
               } else {
                   $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora WHERE Nome_Editora LIKE :busca_nome ORDER BY Cod_Editora ASC";
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
          $sql = "SELECT Cod_Editora, Nome_Editora, Telefone, Email FROM editora ORDER BY Cod_Editora ASC";
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
      min-width: 1205px;
      margin-left: 75px;
    }
    
    .input-wrapper {
      flex: 1;
      position: relative;
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
    }
  </style>
</head>

<body>
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
        <div id="search-container">
          <div class="input-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
            <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
          </svg>
            <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome da editora..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>">
          </div>
          
          <button type="submit" class="btn-filtrar">Buscar</button>
          <button type="button" class="btn-limpar" onclick="limparFiltros()">Limpar</button>
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
                 <a href="alterar_editora.php?id=<?= $editora['Cod_Editora'] ?>" class="btn-action btn-edit" title="Alterar">
                   <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                     <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                     <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                   </svg>
                 </a>
                 <a href="excluir_editora.php?id=<?= $editora['Cod_Editora'] ?>" class="btn-action btn-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta editora?')">
                   <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                     <path d="M3 6h18"/>
                     <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                     <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                   </svg>
                 </a>
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
  
  <script src="subtelas_javascript/sidebar.js"></script>
    </div>
</body>
</html>