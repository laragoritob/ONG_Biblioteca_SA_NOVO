<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Consultar Multas - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultar_multa.css"/>
  <!-- Adiciona SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fefefe;
      padding: 20px;
      border-radius: 8px;
      width: 400px;
      position: relative;
    }

    .close {
      position: absolute;
      right: 10px;
      top: 10px;
      font-size: 24px;
      cursor: pointer;
    }

    .linha-dupla-radio {
      display: flex;
      gap: 20px;
      margin: 15px 0;
    }

    .linha-dupla-radio label {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .btn-voltar {
      position: absolute;
      left: 20px;
      top: 20px;
      padding: 8px 15px;
      background-color: #7076c9;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }

    .btn-voltar:hover {
      background-color: #4c52a7;
    }

    .botoes-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin-top: 30px;
    }

    .botao-mudar {
      background: #ffbcfc;
      color: rgb(0, 0, 0);
      padding: 15px 40px;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      font-weight: bold;
      text-decoration: none;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 0px;
    }

    .botao-mudar.glow {
      box-shadow: 0px 0px 15px #884b85;
    }

    .botao-mudar:hover {
      background: #e07ddb;
      transform: translateY(-2px);
      box-shadow: 0 0 20px #884b85;
    }
  </style>
</head>
<body>

  <header> 
    <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
    <h1> MULTAS </h1>
</header>

<div id="search-container">
  <div class="input-wrapper">
    <span class="icon">🔎</span>
    <input type="text" id="search-input" name="nome_relatorio" placeholder="Buscar livro..." required>
  </div>
</div>
  
  <table id="multas-table" border="1">
    <thead>
      <tr>
        <th>ID da Multa</th>
        <th>Nome do Cliente</th>
        <th>Data da Multa</th>
        <th>Valor</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>#0001</td>
        <td><a class="detalhes-livro">Matheus Dela</a></td>
        <td>03/05/2025</td>
        <td>R$5,20</td>
        <td>Disponível</td>
      </tr>
      <tr>
        <td>#0002</td>
        <td><a class="detalhes-livro2">Helena Lopes</a></td>
        <td>21/04/2025</td>
        <td>R$7,80</td>
        <td>Disponível</td>
      </tr>
      <tr>
        <td>#0003</td>
        <td><a class="detalhes-livro3">Ian Lucas Borba</a></td>
        <td>25/05/2025</td>
        <td>R$0,80</td>
        <td>Disponível</td>
      </tr>
      <tr>
        <td>#0004</td>
        <td><a class="detalhes-livro4">João Vitor Atanazio</a></td>
        <td>30/04/2025</td>
        <td>R$5,80</td>
        <td>Disponível</td>
      </tr>
    </tbody>
  </table>

  <div class="botoes-container">
    <button class="botao-mudar" id="botao-mudar">Mudar status</button>
  </div>

  <div id="modal-status" class="modal">
    <div class="modal-content">
      <span id="close-status" class="close">&times;</span>
      <h2>Mudar Status da Multa</h2>
      <form id="form-status">
        <p>Selecione o novo status:</p>
        <div class="linha-dupla-radio">
          <label>
            <input type="radio" name="novo-status" value="Disponível" required>Disponível
          </label>
          <label>
            <input type="radio" name="novo-status" value="Indisponível" required>Indisponível
          </label>
        </div>
        <div style="text-align: center; margin-top: 15px;">
          <button type="submit">Confirmar</button>
          <button type="button" id="cancelar-status">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script src="subtelas_javascript/consultar_multa.js"></script>
</body>
</html>
