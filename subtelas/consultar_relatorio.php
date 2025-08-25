<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/telconsultar_func.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <br>
    <header>
        <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
        <h1>Consultar Relatórios</h1>
    </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" name="nome_funcionario" placeholder="Buscar funcionario..." required>
    </div>
  </div>
  
  <nav>
    <table id="funcionarios-table" >
      <thead>
        <tr>
          <th>NOME DO RELATORIO</th>
          <th>DATA DE EMISSÃO</th>
          <th>TIPO ARQUIVO</th>
        </tr>
      </thead>
  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
</body>
</html>