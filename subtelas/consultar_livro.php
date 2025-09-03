<?php
session_start();
require_once '../conexao.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4 && $_SESSION['perfil'] != 5) {
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

// Consulta todos os livros
$sql = "SELECT 
          l.Cod_Livro AS id_livro,
          l.Titulo AS titulo,
          a.Nome_Autor AS autor,
          e.Nome_Editora AS editora,
          d.Nome_Doador AS doador,
          l.Data_Lancamento AS data_lancamento,
          l.Data_Registro AS data_registro,
          l.Quantidade AS quantidade,
          l.Num_Prateleira AS prateleira
        FROM livro l
        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor
        LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora
        LEFT JOIN doador d ON l.Cod_Doador = d.Cod_Doador
        ORDER BY l.Titulo ASC";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro na consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Consultar Livros</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
      a {
        text-decoration: none;
    }
    
    .titulo-clicavel {
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

<body>
    <div class="page-wrapper">
      <header>
        <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
      <h1>Consultar Livros</h1>
  </header>

  <div class="filtro-container">
      <form action="consultar_livro.php" method="POST" id="search-container">
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
  
  <nav>
    <table id="funcionarios-table">
        <tr>
          <th>ID</th>
          <th>TÍTULO</th>
          <th>AUTOR</th>
          <th>EDITORA</th>
          <th>DOADOR</th>
          <th>DATA LANÇAMENTO</th>
          <th>DATA REGISTRO</th>
          <th>QUANTIDADE</th>
          <th>PRATELEIRA</th>
          <th>AÇÕES</th>
        </tr>

        <?php if (count($livros) > 0): ?>
          <?php foreach ($livros as $livro): ?>
            <tr>
              <td><?= htmlspecialchars($livro['id_livro']) ?></td>
              <td class="titulo-clicavel" data-livro-id="<?= $livro['id_livro'] ?>"><?= htmlspecialchars($livro['titulo']) ?></td>
              <td><?= htmlspecialchars($livro['autor'] ?? 'Não informado') ?></td>
              <td><?= htmlspecialchars($livro['editora'] ?? 'Não informado') ?></td>
              <td><?= htmlspecialchars($livro['doador'] ?? 'Não informado') ?></td>
              <td><?= htmlspecialchars($livro['data_lancamento'] ?? '') ?></td>
              <td><?= htmlspecialchars($livro['data_registro'] ?? '') ?></td>
              <td><?= htmlspecialchars($livro['quantidade'] ?? '0') ?></td>
              <td><?= htmlspecialchars($livro['prateleira'] ?? '') ?></td>
              <td>
                    <a href="alterar_livro.php?id=<?= $livro['id_livro'] ?>" class="btn-action btn-edit" title="Alterar">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <a href="excluir_livro.php?id=<?= $livro['id_livro'] ?>" class="btn-action btn-delete" title="Excluir">
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
            <tr><td colspan="10">Nenhum livro encontrado</td></tr>
        <?php endif; ?>
    </table>
  </nav>
  <script src="subtelas_javascript/sidebar.js"></script>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Selecionar todos os títulos clicáveis
      const titulosClicaveis = document.querySelectorAll('.titulo-clicavel');
      
      // Adicionar evento de clique para cada título
      titulosClicaveis.forEach(function(titulo) {
        titulo.addEventListener('click', function() {
          const livroId = this.getAttribute('data-livro-id');
          abrirFichaLivro(livroId);
        });
      });
    });
    
    // Função para abrir a ficha do livro
    function abrirFichaLivro(livroId) {
      window.location.href = `ficha_livro.php?id=${livroId}`;
    }
    
    function editarLivro(id) {
      window.location.href = 'alterar_livro.php?id=' + id;
    }
    
    function excluirLivro(id) {
      if (confirm('Tem certeza que deseja excluir este livro?')) {
        // Redirecionar para a página de exclusão com o ID do livro
        window.location.href = 'excluir_livro.php?id=' + id;
      }
    }
    </script>
</body>
</html>
