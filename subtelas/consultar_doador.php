<?php
session_start();
require_once '../conexao.php';

// Consulta todos os doadores
$sql = "SELECT
          d.Cod_Doador      AS id_doador,
          d.Nome_Doador     AS nome,
          d.Telefone        AS telefone,
          d.Email           AS email
        FROM doador d
        ORDER BY d.Nome_Doador ASC";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $doadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro na consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Consultar Doadores</title>
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
      <h1>Consultar Doadores</h1>
  </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" placeholder="Buscar doador..." onkeyup="filtrarTabela()">
    </div>
  </div>
  
  <nav>
    <table id="funcionarios-table">
        <tr>
          <th>ID</th>
          <th>NOME COMPLETO</th>
          <th>TELEFONE</th>
          <th>E-MAIL</th>
          <th>AÇÕES</th>
        </tr>

        <?php if (count($doadores) > 0): ?>
          <?php foreach ($doadores as $d): ?>
            <tr>
              <td><?= htmlspecialchars($d['id_doador']) ?></td>
              <td><?= htmlspecialchars($d['nome']) ?></td>
              <td><?= htmlspecialchars($d['telefone']) ?></td>
              <td><?= htmlspecialchars($d['email']) ?></td>
              <td>
                <button onclick="editarDoador(<?= $d['id_doador'] ?>)">✏️</button>
                <button onclick="excluirDoador(<?= $d['id_doador'] ?>)">🗑️</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5">Nenhum doador encontrado</td></tr>
        <?php endif; ?>
    </table>
  </nav>

  <script src="subtelas_javascript/telconsultar_doadores.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
    </div>
</body>
</html>
