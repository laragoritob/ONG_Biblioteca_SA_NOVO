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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <br>
  <header>
      <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
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
                <button onclick="editarFuncionario(<?= $f['id_funcionario'] ?>)">✏️ Editar</button>
                <button onclick="desativarFuncionario(<?= $f['id_funcionario'] ?>)">❌ Desativar</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">Nenhum funcionário encontrado</td></tr>
        <?php endif; ?>
    </table>
  </nav>

  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
</body>
</html>
