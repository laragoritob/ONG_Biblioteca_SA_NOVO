<?php
  session_start();
  require_once '../conexao.php';

  if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
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
  $funcionarios = [];
  $erro = null;
  $filtro_perfil = isset($_POST['filtro_perfil']) ? $_POST['filtro_perfil'] : '';

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA O CLIENTE PELO ID, NOME OU PERFIL
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $busca = isset($_POST['busca']) ? trim($_POST['busca']) : '';
          $filtro_perfil = isset($_POST['filtro_perfil']) ? $_POST['filtro_perfil'] : '';
          
          // CONSTRÓI A CONSULTA SQL BASE
          $sql = "SELECT f.Cod_Funcionario, f.Nome, f.CPF, f.Email, f.Sexo, f.Telefone, f.Data_Nascimento, f.Data_Efetivacao, f.CEP, f.UF, f.Cidade, f.Bairro, f.Rua, f.Num_Residencia, f.Foto, pf.Nome_Perfil 
                    FROM funcionario f 
                    LEFT JOIN perfil_funcionario pf ON f.Cod_Perfil = pf.Cod_Perfil 
                    WHERE 1=1";
          
          $params = [];
          
          // ADICIONA FILTRO POR PERFIL SE SELECIONADO
          if (!empty($filtro_perfil)) {
              $sql .= " AND f.Cod_Perfil = :filtro_perfil";
              $params[':filtro_perfil'] = $filtro_perfil;
          }
          
          // ADICIONA FILTRO POR BUSCA SE FORNECIDA
          if (!empty($busca)) {
              if (is_numeric($busca)) {
                  $sql .= " AND f.Cod_Funcionario = :busca";
                  $params[':busca'] = $busca;
              } else {
                  $sql .= " AND f.Nome LIKE :busca_nome";
                  $params[':busca_nome'] = "$busca%";
              }
          }
          
          $sql .= " ORDER BY f.Cod_Funcionario ASC";
          
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
          // BUSCA TODOS OS funcionarios
          $sql = "SELECT f.Cod_Funcionario, f.Nome, f.CPF, f.Email, f.Sexo, f.Telefone, f.Data_Nascimento, f.Data_Efetivacao, f.CEP, f.UF, f.Cidade, f.Bairro, f.Rua, f.Num_Residencia, f.Foto, pf.Nome_Perfil 
                    FROM funcionario f 
                    LEFT JOIN perfil_funcionario pf ON f.Cod_Perfil = pf.Cod_Perfil 
                    ORDER BY f.Cod_Funcionario ASC";
          
          $stmt = $pdo->prepare($sql);
      }

      $stmt->execute();
      $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      // GARANTIR QUE $funcionarios SEJA SEMPRE UM ARRAY
      if (!is_array($funcionarios)) {
          $funcionarios = [];
      }
      
  } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
      $funcionarios = [];
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
    a {
      text-decoration: none;
    }
    
    .nome-clicavel {
      cursor: pointer;
      color: #667eea;
      text-decoration: none;
    }

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
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="page-wrapper">
      <header>
        <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
      <h1>Consultar Funcionários</h1>
  </header>

  <form method="POST" action="consultar_funcionario.php">
      <div class="filtro-container">
        <div id="search-container">
          <div class="input-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
            <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
          </svg>
            <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>">
          </div>
          
          <select name="filtro_perfil" class="filtro-select">
            <option value="">Todos os perfis</option>
            <option value="1" <?= $filtro_perfil == '1' ? 'selected' : '' ?>>Gerente</option>
            <option value="2" <?= $filtro_perfil == '2' ? 'selected' : '' ?>>Gestor</option>
            <option value="3" <?= $filtro_perfil == '3' ? 'selected' : '' ?>>Bibliotecário</option>
            <option value="4" <?= $filtro_perfil == '4' ? 'selected' : '' ?>>Recreador</option>
            <option value="5" <?= $filtro_perfil == '5' ? 'selected' : '' ?>>Repositor</option>
          </select>
          
          <button type="submit" class="btn-filtrar">Filtrar</button>
          <button type="button" class="btn-limpar" onclick="limparFiltros()">Limpar Filtros</button>
        </div>
      </div>
    </form>
  
  <nav>
    <table id="funcionarios-table">
        <tr>
          <th>ID</th>
          <th>NOME COMPLETO</th>
          <th>CARGO</th>
          <th>DATA DE NASCIMENTO</th>
          <th>DATA EFETIVAÇÃO</th>
          <th>AÇÕES</th>
        </tr>

        <?php if (count($funcionarios) > 0): ?>
          <?php foreach ($funcionarios as $f): ?>
            <tr>
              <td><?= htmlspecialchars($f['Cod_Funcionario']) ?></td>
              <td class="nome-clicavel" data-funcionario-id="<?= $f['Cod_Funcionario'] ?>"><?= htmlspecialchars($f['Nome']) ?></td>
              <td><?= htmlspecialchars($f['Nome_Perfil']) ?></td>
              <td><?= date("d/m/Y", strtotime($f['Data_Nascimento'])) ?></td>
              <td><?= date("d/m/Y", strtotime($f['Data_Efetivacao'])) ?></td>
              <td>
                    <a href="alterar_funcionario.php?id=<?= $f['Cod_Funcionario'] ?>" class="btn-action btn-edit" title="Alterar">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <a href="excluir_funcionario.php?id=<?= $f['Cod_Funcionario'] ?>" class="btn-action btn-delete" title="Excluir">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                      </svg>
                    </a>
                  </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhum funcionário encontrado</td></tr>
        <?php endif; ?>
    </table>
  </nav>

  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>

  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Selecionar todos os nomes clicáveis
    const nomesClicaveis = document.querySelectorAll('.nome-clicavel');
    
    // Adicionar evento de clique para cada nome
    nomesClicaveis.forEach(function(nome) {
      nome.addEventListener('click', function() {
        const funcionarioId = this.getAttribute('data-funcionario-id');
        abrirFichaFuncionario(funcionarioId);
      });
    });
  });
  
  // Função para abrir a ficha do funcionário
  function abrirFichaFuncionario(funcionarioId) {
    window.location.href = `ficha_funcionario.php?id=${funcionarioId}`;
  }
  </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
    </div>
</body>
</html>
