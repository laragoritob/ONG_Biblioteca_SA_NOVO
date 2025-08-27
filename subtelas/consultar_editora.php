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
        <h1>Consultar Editoras</h1>
    </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" name="nome_autor" placeholder="Buscar autor..." required>
    </div>
  </div>
  
  <nav>
    <table id="funcionarios-table" >
      <thead>
        <tr>
          <th> ID <th>
          <th>NOME DA EDITORA</th>
          <th>CONTATO</th>
        </tr>
      </thead>
  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
    </div>
</body>
</html>