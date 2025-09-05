<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Apenas Gerente (perfil 1) e Bibliotecário (perfil 3) podem consultar autores
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
    case 3: // Bibliotecário - pode consultar autores
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

// Inicializa a variável para armazenar os resultados da consulta
$autor = [];
$mostrar_inativos = isset($_GET['inativos']) && $_GET['inativos'] === 'true';

// Processa reativação se solicitada
if (isset($_GET['reativar']) && is_numeric($_GET['reativar'])) {
    $id_reativar = intval($_GET['reativar']);
    try {
        $sql_reativar = "UPDATE autor SET status = 'ativo' WHERE Cod_Autor = :id";
        $stmt_reativar = $pdo->prepare($sql_reativar);
        $stmt_reativar->bindParam(':id', $id_reativar, PDO::PARAM_INT);
        
        if ($stmt_reativar->execute()) {
            $sucesso_reativar = "Autor reativado com sucesso!";
        } else {
            $erro_reativar = "Erro ao reativar autor.";
        }
    } catch (PDOException $e) {
        $erro_reativar = "Erro ao reativar autor: " . $e->getMessage();
    }
}

// Verifica se o formulário foi enviado e há um termo de busca
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])){
    // Remove espaços em branco do termo de busca
    $busca = trim($_POST['busca']);

    // Verifica se a busca é um número (ID) ou um nome
    $status_condicao = $mostrar_inativos ? "status = 'inativo'" : "status = 'ativo'";
    
    if(is_numeric($busca)){
        // Se for numérico, busca por ID do autor
        $sql = "SELECT * FROM autor WHERE Cod_Autor = :busca AND $status_condicao ORDER BY Cod_Autor ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
    } else {
        // Se for texto, busca por nome do autor (busca parcial)
        $sql = "SELECT * FROM autor WHERE Nome_Autor LIKE :busca_nome_autor AND $status_condicao ORDER BY Cod_Autor ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':busca_nome_autor', "$busca%", PDO::PARAM_STR);
    }
    
    try {
        // Executa a consulta e busca todos os resultados
        $stmt->execute();
        $autor = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Em caso de erro na consulta, exibe mensagem e para execução
        die("Erro na consulta: " . $e->getMessage());
    }
} else {
    // Se não há busca, lista todos os autores ordenados por ID
    $status_condicao = $mostrar_inativos ? "status = 'inativo'" : "status = 'ativo'";
    $sql = "SELECT * FROM autor WHERE $status_condicao ORDER BY Cod_Autor ASC";
    
    try {
        // Prepara e executa a consulta para buscar todos os autores
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $autor = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Em caso de erro na consulta, exibe mensagem e para execução
        die("Erro na consulta: " . $e->getMessage());
    }
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
    
    .status-buttons {
      display: flex;
      gap: 10px;
      align-items: center;
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
        <h1>Consultar Autores</h1>
    </header>
    <div class="filtro-container">
      <form action="consultar_autor.php" method="POST" id="search-container">
        <div class="input-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
          <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
        </svg>
          <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>" onkeyup="filtrarTabela()">
        </div>
        
        <button type="submit" class="btn-filtrar">Buscar</button>
        <button type="button" class="btn-limpar" onclick="limparFiltros()">Limpar</button>

        <div class="status-buttons">
        <?php if (!$mostrar_inativos): ?>
          <a href="consultar_autor.php?inativos=true" class="btn-status btn-inativos">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
            </svg>
            Ver Inativos
          </a>
        <?php else: ?>
          <a href="consultar_autor.php" class="btn-status btn-ativos">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
            </svg>
            Ver Ativos
          </a>
        <?php endif; ?>
      </div>
        </div>
      </form>

<nav>
  <table id="funcionarios-table">
      <tr>
          <th>ID</th>
          <th>NOME DO AUTOR</th>
          <th>TELEFONE</th>
          <th>EMAIL</th>
          <th>AÇÕES</th>
      </tr>

      <?php if(!empty($autor)):?>
          <?php foreach($autor as $autor_item):?>
          <tr>
              <td><?= htmlspecialchars($autor_item['Cod_Autor']) ?></td>
              <td class="nome-clicavel" data-autor-id="<?= $autor_item['Cod_Autor'] ?>"><?= htmlspecialchars($autor_item['Nome_Autor']) ?></td>
              <td><?= htmlspecialchars($autor_item['Telefone']) ?></td>
              <td><?= htmlspecialchars($autor_item['Email']) ?></td>
              <td>
                  <?php if ($mostrar_inativos): ?>
                    <a href="consultar_autor.php?reativar=<?= $autor_item['Cod_Autor'] ?>&inativos=true" class="btn-action btn-reactivate" title="Reativar" onclick="return confirm('Deseja reativar este autor?')">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"/>
                      </svg>
                    </a>
                  <?php else: ?>
                    <a href="alterar_autor.php?id=<?= $autor_item['Cod_Autor'] ?>" class="btn-action btn-edit" title="Alterar">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <a href="excluir_autor.php?id=<?= $autor_item['Cod_Autor'] ?>" class="btn-action btn-delete" title="Inativar">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                      </svg>
                    </a>
                  <?php endif; ?>
                </td>
           </tr>
          <?php endforeach;?>
      <?php else:?>
          <tr><td colspan="5">Nenhum autor encontrado</td></tr>
      <?php endif;?>
  </table>
</nav>
    </div>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
    
    <!-- Notificações de reativação -->
    <?php if (isset($sucesso_reativar)): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: '<?= addslashes($sucesso_reativar) ?>',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'consultar_autor.php';
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
</body>
    <script>
    function editarAutor(id) {
      window.location.href = 'alterar_autor.php?id=' + id;
    }
    
    // Função para excluir autor
    function excluirAutor(id) {
        if (confirm('Tem certeza que deseja excluir este autor?')) {
            // Redirecionar para a página de exclusão com o ID do autor
            window.location.href = 'excluir_autor.php?id=' + id;
        }
    }
    
    // Função para filtrar tabela em tempo real
    function filtrarTabela() {
        var input = document.getElementById('search-input');
        var filter = input.value.toLowerCase();
        var table = document.getElementById('funcionarios-table');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName('td');
            var found = false;
            
            for (var j = 0; j < td.length; j++) {
                var cell = td[j];
                if (cell) {
                    var txtValue = cell.textContent || cell.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            if (found) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
    
    // Função para limpar filtros
    function limparFiltros() {
        document.getElementById('search-input').value = '';
        filtrarTabela(); // Mostra todas as linhas novamente
    }
  </script>
</html>