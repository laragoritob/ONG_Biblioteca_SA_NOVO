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
                    <a href="alterar_cliente.php?id=<?= $c['Cod_Cliente'] ?>" class="btn-action btn-edit" title="Alterar">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <a href="excluir_cliente.php?id=<?= $c['Cod_Cliente'] ?>" class="btn-action btn-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
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
            <tr><td colspan="5">Nenhum doador encontrado</td></tr>
        <?php endif; ?>
    </table>
  </nav>

  <script src="subtelas_javascript/telconsultar_doadores.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
    </div>
</body>
</html>
