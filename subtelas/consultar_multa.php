<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Gerente (perfil 1), Bibliotecário (perfil 3) e Recreador (perfil 4) podem consultar multas
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4) {
    // Se não tem permissão, exibe alerta e redireciona para login
    echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
    exit();
}

// Define qual página o usuário deve retornar baseado em seu perfil
switch ($_SESSION['perfil']) {
    case 1: // Gerente - pode acessar todas as funcionalidades
        $linkVoltar = "../gerente.php";
        break;
    case 2: // Gestor - não tem acesso a esta página
        $linkVoltar = "../gestor.php";
        break;
    case 3: // Bibliotecário - pode consultar multas
        $linkVoltar = "../bibliotecario.php";
        break;
    case 4: // Recreador - pode consultar multas
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
  $multas = [];
  $erro = null;

  try {
      // SE O FORMULÁRIO FOR ENVIADO, BUSCA A MULTA PELO ID OU NOME DO CLIENTE
      if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
          $busca = trim($_POST['busca']);
          
          // VERIFICA SE A BUSCA É UM NÚMERO (ID) OU UM NOME
          if (is_numeric($busca)) {
              $sql = "SELECT m.Cod_Multa, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                             m.Data_Multa, m.Valor_Multa
                        FROM multa m 
                        INNER JOIN emprestimo e ON m.Cod_Emprestimo = e.Cod_Emprestimo 
                        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                        WHERE m.Cod_Multa = :busca AND (m.Status_Multa IS NULL OR m.Status_Multa != 'Paga')
                        ORDER BY m.Data_Multa DESC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindParam(":busca", $busca, PDO::PARAM_INT);
          } else {
              $sql = "SELECT m.Cod_Multa, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                             m.Data_Multa, m.Valor_Multa
                        FROM multa m 
                        INNER JOIN emprestimo e ON m.Cod_Emprestimo = e.Cod_Emprestimo 
                        INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                        INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                        WHERE c.Nome LIKE :busca_nome AND (m.Status_Multa IS NULL OR m.Status_Multa != 'Paga')
                        ORDER BY m.Data_Multa DESC";
              
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
          }
      } else {
          // BUSCA APENAS MULTAS PENDENTES (não pagas)
          $sql = "SELECT m.Cod_Multa, c.Nome as Nome_Cliente, l.Titulo as Nome_Livro, 
                         m.Data_Multa, m.Valor_Multa
                    FROM multa m 
                    INNER JOIN emprestimo e ON m.Cod_Emprestimo = e.Cod_Emprestimo 
                    INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente 
                    INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro 
                    WHERE m.Status_Multa IS NULL OR m.Status_Multa != 'Paga'
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
  <title>ONG Biblioteca - Consultar Multas</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .btn-pagar {
      background-color: #28a745 !important;
      color: white !important;
      width: 60px !important;
      height: 30px !important;
      border: none !important;
      border-radius: 8px !important;
      font-size: 15px !important;
      text-decoration: none !important;
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    .btn-pagar:hover {
      background-color: #218838 !important;
      transform: translateY(-2px) !important;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
    }
    
    .btn-pagar:active {
      transform: translateY(0) !important;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* CSS para o status das multas */
    .status-pendente {
      color:rgb(88, 66, 0);
      
    }
    
    .status-paga {
      color:rgb(52, 187, 83);
    }
    
    /* CSS para linhas de multas pagas */
    .multa-paga {
      background-color: #e2ffe6 !important;
    }
    
    .multa-paga:hover {
      background-color:rgb(193, 252, 200) !important;
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

        .swal2-title-arial {
            font-family: Arial, sans-serif !important;
            font-weight: bold !important;
        }
        
        .swal2-html-arial {
            font-family: Arial, sans-serif !important;
            font-size: 16px !important;
        }
        
        /* Estilo dos botões igual ao cadastro_funcionario */
        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:focus {
            outline: 2px solid #6366f1 !important;
            outline-offset: 2px !important;
        }
        
        .swal2-cancel {
            background: #dc2626 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-cancel:hover {
            background: #b91c1c !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
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
        <h1>Consultar Multas</h1>
      </header>

      <div class="filtro-container">
      <form action="consultar_multa.php" method="POST" id="search-container">
        <div class="input-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
          <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
        </svg>
          <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>" onkeyup="filtrarTabela()">
        </div>
        
        <button type="submit" class="btn-filtrar">Buscar</button>
        <button type="button" class="btn-limpar" onclick="limparFiltros()">Limpar</button>
      </form>
        </div>
      
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
                <tr id="multa-<?= htmlspecialchars($m['Cod_Multa']) ?>">
                  <td><?= htmlspecialchars($m['Cod_Multa']) ?></td>
                  <td><?= htmlspecialchars($m['Nome_Cliente']) ?></td>
                  <td><?= htmlspecialchars($m['Nome_Livro']) ?></td>
                  <td><?= date("d/m/Y", strtotime($m['Data_Multa'])) ?></td>
                  <td>R$ <?= number_format($m['Valor_Multa'], 2, ',', '.') ?></td>
                  <td>
                    <span class="status-pendente">PENDENTE</span>
                  </td>
                  <td>
                    <button class="btn-pagar" onclick="confirmarPagamento(<?= htmlspecialchars($m['Cod_Multa']) ?>, '<?= htmlspecialchars($m['Nome_Cliente']) ?>', '<?= htmlspecialchars($m['Nome_Livro']) ?>', <?= htmlspecialchars($m['Valor_Multa']) ?>)">Pagar</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Nenhuma multa pendente encontrada</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </nav>

      <script src="subtelas_javascript/consultas.js"></script>

      <script>
        // Função para filtrar tabela pelo input de busca
        function filtrarTabela() {
          const input = document.getElementById("search-input").value.toLowerCase();
          const rows = document.querySelectorAll("#funcionarios-table tbody tr");
          
          rows.forEach(row => {
            const nome = row.cells[1].textContent.toLowerCase();
            const id = row.cells[0].textContent.toLowerCase();
            row.style.display = (nome.includes(input) || id.includes(input)) ? "" : "none";
          });
        }
        
        // Função para confirmar pagamento da multa
        function confirmarPagamento(codMulta, nomeCliente, nomeLivro, valorMulta) {
          Swal.fire({
            title: 'Confirmar Pagamento',
            html: `Tem certeza que deseja marcar como paga a multa do cliente <strong>${nomeCliente}</strong> pelo livro <strong>"${nomeLivro}"</strong> no valor de <strong>R$ ${valorMulta.toFixed(2).replace('.', ',')}</strong>?`,
            icon: 'warning',
                confirmButtonText: 'Sim, Excluir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                showCancelButton: true,
                customClass: {
                    title: 'swal2-title-arial',
                    htmlContainer: 'swal2-html-arial',
                    cancelButton: 'swal2-cancel',
                    confirmButton: 'swal2-confirm',
                }
          }).then((result) => {
            if (result.isConfirmed) {
              // Redireciona para pagar a multa
              window.location.href = `pagar_multa.php?id=${codMulta}`;
            }
          });
        }
        
        // Função para limpar filtros
        function limparFiltros() {
          document.getElementById("search-input").value = "";
          filtrarTabela();
        }
        
        // Adicionar evento de busca em tempo real
        document.getElementById("search-input").addEventListener("input", filtrarTabela);
      </script>
    </div>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>