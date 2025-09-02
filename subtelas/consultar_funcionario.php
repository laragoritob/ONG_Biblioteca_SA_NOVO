<?php
session_start();
require_once '../conexao.php';

// Consulta todos os funcionários
$sql = "SELECT
          f.Cod_Funcionario  AS id_funcionario,
          f.Nome             AS nome,
          f.Data_Nascimento  AS data_nascimento,
          f.Data_Efetivacao  AS data_efetivacao,
          p.Nome_Perfil      AS perfil
        FROM funcionario f
        JOIN perfil_funcionario p ON f.Cod_Perfil = p.Cod_Perfil
        ORDER BY f.Nome ASC";

try {
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Erro na consulta: " . $e->getMessage());
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
</head>

<body>
    <div class="page-wrapper">
      <header>
        <form action="../gerente.php" method="POST">
            <button class="btn-voltar">← Voltar</button>
        </form>
      <h1>Consultar Funcionários</h1>
  </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" placeholder="Buscar funcionário..." onkeyup="filtrarTabela()">
    </div>
  </div>
  
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
              <td><?= htmlspecialchars($f['id_funcionario']) ?></td>
              <td><?= htmlspecialchars($f['nome']) ?></td>
              <td><?= htmlspecialchars($f['perfil']) ?></td>
              <td><?= date("d/m/Y", strtotime($f['data_nascimento'])) ?></td>
              <td><?= date("d/m/Y", strtotime($f['data_efetivacao'])) ?></td>
              <td>
                    <a href="alterar_funcionario.php?id=<?= $f['id_funcionario'] ?>" class="btn-action btn-edit" title="Alterar">
                      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                      </svg>
                    </a>
                    <a href="excluir_funcionario.php?id=<?= $f['id_funcionario'] ?>" class="btn-action btn-delete" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">
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
  <script src="subtelas_javascript/sidebar.js"></script>
    </div>
</body>
</html>
