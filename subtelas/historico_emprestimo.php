<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/telhistorico.css" />
</head>

<body>
  <header>
    <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
    <h1>Histórico de Empréstimos</h1>
  </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" name="nome_relatorio" placeholder="Buscar livro..." required>
    </div>
  </div>
  
  <nav>
    <table id="livros-table" border="1">
      <thead>
        <tr>
          <th>CÓDIGO EMPRÉSTIMO</th>
          <th>NOME DO LIVRO</th>
          <th>NOME DO CLIENTE</th>
          <th>EMPRÉSTIMO</th>
          <th>DEVOLUÇÃO</th>
          <th>STATUS</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>0001</td>
          <td><a href="#" class="detalhes-livro">Para Todos Os Garotos Que Já Amei</a></td>
          <td>Helena Lopes</td>
          <td>13/04/2025</td>
          <td>20/04/2025</td>
          <td></td>
        </tr>
        <tr>
          <td>0002</td>
          <td><a href="#" class="detalhes-livro2">O Cortiço</a></td>
          <td>Gustavo Tobler</td>
          <td>23/05/2025</td>
          <td>30/05/2025</td>
          <td></td>
        </tr>
        <tr>
          <td>0003</td>
          <td><a href="#" class="detalhes-livro3">O Pequeno Príncipe</a></td>
          <td>Matheus Dela</td>
          <td>26/04/2025</td>
          <td>02/05/2025</td>
          <td></td>
        </tr>
        <tr>
          <td>0004</td>
          <td><a href="#" class="detalhes-livro4">Matemática Básica</a></td>
          <td>Matheus Rodrigues</td>
          <td>24/05/2025</td>
          <td>31/05/2025</td>
          <td></td>
        </tr>
        <tr>
          <td>0005</td>
          <td><a href="#" class="detalhes-livro5">Crepúsculo</a></td>
          <td>Ian Lucas Borba</td>
          <td>27/05/2025</td>
          <td>03/06/2025</td>
          <td></td>
        </tr>
        <tr>
          <td>0006</td>
          <td><a href="#" class="detalhes-livro6">FNAF</a></td>
          <td>João Vitor Atanazio</td>
          <td>28/05/2025</td>
          <td>04/06/2025</td>
          <td></td>
        </tr>
        
      </tbody>
    </table>
  </nav>

  <!-- Modal -->
  <div id="modal" style="display:none;">
    <div id="modal-content">
      <span id="close-modal" style="cursor:pointer;">&times;</span>
      <div id="modal-body"></div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="subtelas_javascript/tela_renovacao_devolucao.js"></script>
</body>
</html>

