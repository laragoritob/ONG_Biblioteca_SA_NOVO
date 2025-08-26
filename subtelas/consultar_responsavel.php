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
        <h1>Consultar Responsáveis</h1>
    </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" name="nome_responsavel" placeholder="Buscar responsavel..." required>
    </div>
  </div>
  
  <nav>
    <table id="funcionarios-table" >
      <thead>
        <tr>
          <th>ID</th>
          <th>NOME DO RESPONSÁVEL</th>
          <th>E-MAIL</th>
          <th>TELEFONE</th>
          <th>DATA NASCIMENTO</th>
          <th>AÇÕES</th>
        </tr>
      </thead>
  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
</body>
</html>