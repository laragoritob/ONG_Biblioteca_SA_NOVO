<?php
session_start();
require_once '../conexao.php';

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

<body>
    <div class="page-wrapper">
      <header>
        <form action="../gerente.php" method="POST">
            <button class="btn-voltar">← Voltar</button>
        </form>
      <h1>Consultar Livros</h1>
  </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" placeholder="Buscar livro..." onkeyup="filtrarTabela()">
    </div>
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
              <td><?= htmlspecialchars($livro['titulo']) ?></td>
              <td><?= htmlspecialchars($livro['autor'] ?? 'Não informado') ?></td>
              <td><?= htmlspecialchars($livro['editora'] ?? 'Não informado') ?></td>
              <td><?= htmlspecialchars($livro['doador'] ?? 'Não informado') ?></td>
              <td><?= htmlspecialchars($livro['data_lancamento'] ?? '') ?></td>
              <td><?= htmlspecialchars($livro['data_registro'] ?? '') ?></td>
              <td><?= htmlspecialchars($livro['quantidade'] ?? '0') ?></td>
              <td><?= htmlspecialchars($livro['prateleira'] ?? '') ?></td>
              <td>
              <button style="margin-right: 0.01rem;" onclick="editarLivro(<?= $livro['id_livro'] ?>)">✏️</button>
              <button style="margin-left: 0.01rem;" onclick="excluirLivro(<?= $livro['id_livro'] ?>)">🗑️</button>
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
