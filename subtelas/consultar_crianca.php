<?php
session_start();
require_once '../conexao.php';

// Consulta todas as crianças
$sql = "SELECT 
          Cod_Crianca     AS ID Criança
          Nome            AS Nome
          Sexo            AS sexo
          Data_Nascimento AS data_nascimento
          Nome_Responsavel AS responsavel
          Telefone        AS telefone
        FROM crianca
        ORDER BY Nome ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $criancas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
      <h1>Consultar Crianças</h1>
  </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" placeholder="Buscar criança..." onkeyup="filtrarTabela()">
    </div>
  </div>
  
  <nav>
    <table id="criancas-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>NOME</th>
          <th>SEXO</th>
          <th>DATA DE NASCIMENTO</th>
          <th>NOME RESPONSÁVEL</th>
          <th>TELEFONE</th>
          <th>AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($criancas)): ?>
          <?php foreach ($criancas as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['ID Criança']) ?></td>
              <td><?= htmlspecialchars($c['nome']) ?></td>
              <td><?= htmlspecialchars($c['sexo']) ?></td>
              <td><?= date("d/m/Y", strtotime($c['data_nascimento'])) ?></td>
              <td><?= htmlspecialchars($c['responsavel']) ?></td>
              <td><?= htmlspecialchars($c['telefone']) ?></td>
              <td>
                <button onclick="editarCrianca(<?= $c['ID Criança'] ?>)">✏️ Editar</button>
                <button onclick="excluirCrianca(<?= $c['ID Criança'] ?>)">❌ Excluir</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7">Nenhuma criança encontrada</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </nav>

  <script src="subtelas_javascript/telconsultar_criancas.js"></script>
  <script>
    // Função para filtrar tabela pelo input de busca
    function filtrarTabela() {
      const input = document.getElementById("search-input").value.toLowerCase();
      const rows = document.querySelectorAll("#criancas-table tbody tr");
      
      rows.forEach(row => {
        const nome = row.cells[1].textContent.toLowerCase();
        row.style.display = nome.includes(input) ? "" : "none";
      });
    }
  </script>
</body>
</html>
