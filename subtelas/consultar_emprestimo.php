  <?php
session_start();
require_once '../conexao.php';

// Verifica permissão: Gerente (1), Bibliotecário (3), Recreador (4)
if (!in_array($_SESSION['perfil'], [1, 3, 4])) {
    echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
    exit();
}

// Define link de retorno por perfil
switch ($_SESSION['perfil']) {
    case 1: $linkVoltar = "../gerente.php"; break;
    case 3: $linkVoltar = "../bibliotecario.php"; break;
    case 4: $linkVoltar = "../recreador.php"; break;
    default: $linkVoltar = "../index.php"; break;
}

// Inicializa variáveis
$emprestimos = [];
$emprestimos_todos = [];
$erro = null;
$mostrar_inativos = isset($_GET['inativos']) && $_GET['inativos'] === 'true';

// Reativação
if (isset($_GET['reativar']) && is_numeric($_GET['reativar'])) {
    $id_reativar = intval($_GET['reativar']);
    try {
        $sql_reativar = "UPDATE emprestimo SET Status_Emprestimo = 'ativo' WHERE Cod_Emprestimo = :id";
        $stmt_reativar = $pdo->prepare($sql_reativar);
        $stmt_reativar->bindParam(':id', $id_reativar, PDO::PARAM_INT);
        $stmt_reativar->execute();
        $sucesso_reativar = "Empréstimo reativado com sucesso!";
    } catch (PDOException $e) {
        $erro_reativar = "Erro ao reativar empréstimo: " . $e->getMessage();
    }
}

try {
    // Define filtro de status
    $statusFiltro = $mostrar_inativos ? "e.Status_Emprestimo = 'inativo'" : "e.Status_Emprestimo = 'Pendente'";

    // Busca por POST
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])) {
        $busca = trim($_POST['busca']);
        if (is_numeric($busca)) {
            $sql = "SELECT e.Cod_Emprestimo, c.Nome AS Nome_Cliente, l.Titulo AS Nome_Livro,
                           e.Data_Emprestimo, e.Data_Devolucao, e.Status_Emprestimo
                      FROM emprestimo e
                      INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente
                      INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro
                      WHERE e.Cod_Emprestimo = :busca AND $statusFiltro
                      ORDER BY e.Data_Emprestimo DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
        } else {
            $sql = "SELECT e.Cod_Emprestimo, c.Nome AS Nome_Cliente, l.Titulo AS Nome_Livro,
                           e.Data_Emprestimo, e.Data_Devolucao, e.Status_Emprestimo
                      FROM emprestimo e
                      INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente
                      INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro
                      WHERE c.Nome LIKE :busca_nome AND $statusFiltro
                      ORDER BY e.Data_Emprestimo DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
        }
    } else {
        // Apenas pendentes ou inativos
        $sql = "SELECT e.Cod_Emprestimo, c.Nome AS Nome_Cliente, l.Titulo AS Nome_Livro,
                       e.Data_Emprestimo, e.Data_Devolucao, e.Status_Emprestimo
                  FROM emprestimo e
                  INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente
                  INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro
                  WHERE $statusFiltro
                  ORDER BY e.Data_Emprestimo DESC";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->execute();
    $emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Histórico completo (devolvidos)
    $sql_todos = "SELECT e.Cod_Emprestimo, c.Nome AS Nome_Cliente, l.Titulo AS Nome_Livro,
                         e.Data_Emprestimo, e.Data_Devolucao, e.Status_Emprestimo
                    FROM emprestimo e
                    INNER JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente
                    INNER JOIN livro l ON e.Cod_Livro = l.Cod_Livro
                    WHERE e.Status_Emprestimo = 'Devolvido'
                    ORDER BY e.Data_Emprestimo DESC";
    $stmt_todos = $pdo->prepare($sql_todos);
    $stmt_todos->execute();
    $emprestimos_todos = $stmt_todos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $erro = "Erro na consulta: " . $e->getMessage();
    $emprestimos = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css" />
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
      <h1>Consultar Empréstimos</h1>
    </header>
   
    
    <?php 
    // Variáveis para SweetAlert2
    $sucesso_swal = '';
    $erro_swal = '';
    
    // Verificar mensagens de sucesso ou erro
    if (isset($_GET['sucesso'])) {
        if (isset($_GET['multa']) && isset($_GET['dias'])) {
            $multa = number_format($_GET['multa'], 2, ',', '.');
            $dias = $_GET['dias'];
            $sucesso_swal = "{
                title: '🎉 Devolução Realizada com Sucesso!',
                html: `
                    <div style='text-align: center;'>
                        <p style='margin: 20px 0; font-size: 16px; color: #059669;'>O livro foi devolvido e o acervo foi atualizado.</p>
                        <div style='background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; margin: 15px 0;'>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>📅 Dias de atraso:</strong> $dias dias</p>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>💰 Multa aplicada:</strong> R$ $multa</p>
                        </div>
                    </div>
                `,
                icon: 'success',
                confirmButtonColor: '#10b981',
                confirmButtonText: 'OK'
            }";
        } else {
            $sucesso_swal = "{
                title: '✨ Devolução Concluída!',
                html: `
                    <div style='text-align: center;'>
                        <p style='margin: 20px 0; font-size: 16px; color: #2563eb;'>O livro foi devolvido com sucesso. Sem multas aplicadas!</p>
                        <div style='background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; margin: 15px 0;'>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>✅ Status:</strong> Devolução no prazo</p>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>📚 Acervo:</strong> Atualizado automaticamente</p>
                        </div>
                    </div>
                `,
                icon: 'success',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'OK'
            }";
        }
        
        // Verificar se há alerta de estoque baixo
        if (isset($_GET['estoque_baixo']) && $_GET['estoque_baixo'] == '1') {
            $quantidade = $_GET['quantidade'];
            $titulo = htmlspecialchars($_GET['titulo']);
            $alerta_estoque_swal = "{
                title: '⚠️ Alerta de Estoque Baixo',
                html: `
                    <div style='text-align: center;'>
                        <p style='margin: 20px 0; font-size: 16px; color: #d97706;'>O livro ainda possui estoque baixo após a devolução!</p>
                        <div style='background: #fffbeb; border: 1px solid #fed7aa; border-radius: 8px; padding: 15px; margin: 15px 0;'>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>📚 Livro:</strong> $titulo</p>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>📦 Quantidade atual:</strong> $quantidade exemplar(es)</p>
                            <p style='margin: 8px 0; font-size: 14px;'><strong>⚠️ Status:</strong> Estoque abaixo de 5 unidades</p>
                        </div>
                    </div>
                `,
                icon: 'warning',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Entendi'
            }";
        }
    }
    
    if (isset($_GET['erro'])) {
        $erro_swal = "{
            title: ' Erro na Operação',
            text: '" . htmlspecialchars($_GET['erro']) . "',
            icon: 'error',
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'OK'
        }";
    }
    
    if (isset($erro)) { ?>
        <div style="text-align: center; padding: 20px; color: #d32f2f; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 20px;">
            <p><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
        </div>
    <?php } ?>
    <form action="consultar_emprestimo.php" method="POST">
    <div class="filtro-container">
      <div id="search-container">
          <div class="input-wrapper">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
            <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
          </svg>
                         <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome do cliente..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>">
          </div>
        
          <button type="submit" class="btn-filtrar">Buscar</button>

                     <div>
             <button type="button" id="btn-historico" class="btn-filtrar" onclick="alternarVisualizacao()">
               Ver Histórico de Devolvidos
             </button>
           </div>
        </div>
    </form>

    <!-- Campo hidden para armazenar dados de empréstimos devolvidos -->
    <div id="dados-completos" style="display: none;">
      <?php echo json_encode($emprestimos_todos); ?>
    </div>

    <nav>
      <table id="funcionarios-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>NOME DO CLIENTE</th>
            <th>NOME DO LIVRO</th>
            <th>DATA DO EMPRÉSTIMO</th>
            <th>DATA DE DEVOLUÇÃO</th>
            <th>STATUS</th>
            <th>AÇÕES</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($emprestimos) && is_array($emprestimos)): ?>
            <?php foreach ($emprestimos as $e): ?>
              <tr>
                <td><?= htmlspecialchars($e['Cod_Emprestimo']) ?></td>
                <td><?= htmlspecialchars($e['Nome_Cliente']) ?></td>
                <td><?= htmlspecialchars($e['Nome_Livro']) ?></td>
                <td><?= date("d/m/Y", strtotime($e['Data_Emprestimo'])) ?></td>
                <td><?= $e['Data_Devolucao'] ? date("d/m/Y", strtotime($e['Data_Devolucao'])) : 'Não devolvido' ?></td>
                <td><?= htmlspecialchars($e['Status_Emprestimo']) ?></td>
                <td>
                  <?php if ($mostrar_inativos): ?>
                    <a href="consultar_emprestimo.php?reativar=<?= $e['Cod_Emprestimo'] ?>&inativos=true" class="btn-action btn-reactivate" title="Reativar" onclick="return confirm('Deseja reativar este empréstimo?')">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                      </svg>
                    </a>
                  <?php else: ?>
                    <a href="renovar_emprestimo.php?id=<?= htmlspecialchars($e['Cod_Emprestimo']) ?>" class="renovar">Renovar</a>
                    <a href="devolver_emprestimo.php?id=<?= htmlspecialchars($e['Cod_Emprestimo']) ?>" class="devolver">Devolver</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr><td colspan="7">Nenhum empréstimo pendente encontrado</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </nav>


    <script>
      let visualizacaoAtual = 'pendentes'; // 'pendentes' ou 'devolvidos'
      let dadosPendentes = [];
      let dadosDevolvidos = [];
      
      // Carregar dados quando a página carregar
      document.addEventListener('DOMContentLoaded', function() {
        // Obter dados da tabela atual (pendentes)
        const tabela = document.getElementById('funcionarios-table');
        const linhas = tabela.querySelectorAll('tbody tr');
        
        dadosPendentes = Array.from(linhas).filter(linha => linha.cells && linha.cells.length >= 6).map(linha => ({
          id: linha.cells[0] ? linha.cells[0].textContent : '',
          cliente: linha.cells[1] ? linha.cells[1].textContent : '',
          livro: linha.cells[2] ? linha.cells[2].textContent : '',
          dataEmprestimo: linha.cells[3] ? linha.cells[3].textContent : '',
          dataDevolucao: linha.cells[4] ? linha.cells[4].textContent : '',
          status: linha.cells[5] ? linha.cells[5].textContent : ''
        }));
        
        // Obter dados de empréstimos devolvidos do campo hidden
        const dadosCompletosElement = document.getElementById('dados-completos');
        if (dadosCompletosElement && dadosCompletosElement.textContent && dadosCompletosElement.textContent.trim()) {
          try {
            dadosDevolvidos = JSON.parse(dadosCompletosElement.textContent);
          } catch (e) {
            console.error('Erro ao fazer parse dos dados devolvidos:', e);
            dadosDevolvidos = [];
          }
        } else {
          dadosDevolvidos = [];
        }
      });
      
      // Função para alternar entre visualizações
      function alternarVisualizacao() {
        const btnHistorico = document.getElementById('btn-historico');
        const tabela = document.getElementById('funcionarios-table');
        const tbody = tabela.querySelector('tbody');
        
        if (visualizacaoAtual === 'pendentes') {
          // Mostrar histórico de empréstimos devolvidos
          visualizacaoAtual = 'devolvidos';
          btnHistorico.textContent = 'Ver Apenas Pendentes';
          btnHistorico.style.backgroundColor = '#e53e3e';
           
           // Limpar tabela e preencher com dados de empréstimos devolvidos
           tbody.innerHTML = '';
           
           if (dadosDevolvidos.length > 0) {
             dadosDevolvidos.forEach(emprestimo => {
               const linha = document.createElement('tr');
               linha.innerHTML = `
                 <td>${emprestimo.Cod_Emprestimo}</td>
                 <td>${emprestimo.Nome_Cliente}</td>
                 <td>${emprestimo.Nome_Livro}</td>
                 <td>${formatarData(emprestimo.Data_Emprestimo)}</td>
                 <td>${emprestimo.Data_Devolucao ? formatarData(emprestimo.Data_Devolucao) : 'Não devolvido'}</td>
                 <td>
                   <span class="status-${emprestimo.Status_Emprestimo.toLowerCase()}">
                     ${emprestimo.Status_Emprestimo}
                   </span>
                 </td>
                 <td>
                   <span style="color: #666; font-style: italic;">Empréstimo finalizado</span>
                 </td>
               `;
               tbody.appendChild(linha);
             });
           } else {
             tbody.innerHTML = '<tr><td colspan="7">Nenhum empréstimo devolvido encontrado</td></tr>';
           }
           
           // Atualizar colspan da mensagem de "nenhum encontrado"
           const mensagemVazia = tbody.querySelector('tr td[colspan]');
           if (mensagemVazia) {
             mensagemVazia.setAttribute('colspan', '7');
           }
           
         } else {
           // Voltar para visualização de pendentes
           visualizacaoAtual = 'pendentes';
           btnHistorico.textContent = 'Ver Histórico de Devolvidos';
           btnHistorico.style.backgroundColor = 'rgb(83, 86, 238)';
          
          // Limpar tabela e preencher com dados pendentes
          tbody.innerHTML = '';
          
          if (dadosPendentes.length > 0) {
            dadosPendentes.forEach(emprestimo => {
              const linha = document.createElement('tr');
              linha.innerHTML = `
                <td>${emprestimo.id}</td>
                <td>${emprestimo.cliente}</td>
                <td>${emprestimo.livro}</td>
                <td>${emprestimo.dataEmprestimo}</td>
                <td>${emprestimo.dataDevolucao}</td>
                <td>${emprestimo.status}</td>
                <td>
                  <a href="renovar_emprestimo.php?id=${emprestimo.id}" class="renovar">Renovar</a>
                  <a href="devolver_emprestimo.php?id=${emprestimo.id}" class="devolver">Devolver</a>
                </td>
              `;
              tbody.appendChild(linha);
            });
          } else {
            tbody.innerHTML = '<tr><td colspan="7">Nenhum empréstimo pendente encontrado</td></tr>';
          }
        }
      }
      
      // Função para formatar data
      function formatarData(dataString) {
        if (!dataString) return 'Não informado';
        const data = new Date(dataString);
        if (isNaN(data.getTime())) return dataString;
        return data.toLocaleDateString('pt-BR');
      }
      
      // Função para filtrar tabela pelo input de busca
      function filtrarTabela() {
        const input = document.getElementById("search-input").value.toLowerCase();
        const rows = document.querySelectorAll("#funcionarios-table tbody tr");
        
        rows.forEach(row => {
          const nome = row.cells[1].textContent.toLowerCase();
          row.style.display = nome.includes(input) ? "" : "none";
        });
      }
    </script>
    </div>
          <!-- Dados da tabela serão carregados via JavaScript -->
        </tbody>
      </table>
    </nav>
  </div>


  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>

  <!-- Notificações de reativação -->
  <?php if (isset($sucesso_reativar)): ?>
  <script>
      Swal.fire({
          icon: 'success',
          title: 'Sucesso!',
          text: '<?= addslashes($sucesso_reativar) ?>',
          confirmButtonText: 'OK'
      }).then(() => {
          window.location.href = 'consultar_emprestimo.php';
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
  
  <script>
    // Exibir SweetAlert2 para mensagens de sucesso ou erro
    <?php if (!empty($sucesso_swal)): ?>
      Swal.fire(<?php echo $sucesso_swal; ?>).then(() => {
        <?php if (!empty($alerta_estoque_swal)): ?>
          // Mostra alerta de estoque baixo após o sucesso
          Swal.fire(<?php echo $alerta_estoque_swal; ?>);
        <?php endif; ?>
      });
    <?php endif; ?>
    
    <?php if (!empty($erro_swal)): ?>
      Swal.fire(<?php echo $erro_swal; ?>);
    <?php endif; ?>
  </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>
