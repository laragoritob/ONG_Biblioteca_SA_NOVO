<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Controle de Estoque - Sala Arco-íris</title>
  
  <!-- Estilo da página -->
  <link rel="stylesheet" type="text/css" href="subtelas_css/controleestoque.css"/>
  
  <!-- Biblioteca de alertas (SweetAlert2) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<!-- Cabeçalho principal da página -->
<header>
  <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
  <h1> LIVROS </h1>
</header>

<!-- Campo de busca -->
<div id="search-container">
  <div class="input-wrapper">
    <span class="icon">🔎</span>
    <input type="text" id="search-input" name="nome_relatorio" placeholder="Buscar livro..." required>
  </div>
</div>

<!-- Tabela de exibição dos livros cadastrados -->
<table id="livros-table" border="1">
  <thead>
    <tr>
      <th>ID do Livro</th>
      <th>Nome do Livro</th>
      <th>Gênero</th>
      <th>Autor</th>
      <th>Quantidade em Estoque</th>
      <th>Prateleira</th>
      <th>Data</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <!-- Linhas de exemplo de livros -->
    <tr>
      <td>#0001</td>
      <td><a href="#" class="detalhes-livro">Para Todos Os Garotos Que Já Amei</a></td>
      <td>Romance</td>
      <td>Jenny Han</td>
      <td>30</td>
      <td>05</td>
      <td>30/07/2024</td>
      <td><span class="status-badge ativo">Ativo</span></td>
    </tr>
    <!-- Mais exemplos -->
    <tr>
      <td>#0002</td>
      <td><a href="#" class="detalhes-livro2">O Cortiço</a></td>
      <td>Naturalismo</td>
      <td>Aluísio Azevedo</td>
      <td>12</td>
      <td>03</td>
      <td>01/08/2024</td>
      <td><span class="status-badge ativo">Ativo</span></td>
    </tr>
    <tr>
      <td>#0003</td>
      <td><a href="#" class="detalhes-livro3">O Pequeno Príncipe</a></td>
      <td>Clássico</td>
      <td>Antoine de Saint-Exupéry</td>
      <td>20</td>
      <td>02</td>
      <td>15/09/2024</td>
      <td><span class="status-badge ativo">Ativo</span></td>
    </tr>
    <tr>
      <td>#0004</td>
      <td><a href="#" class="detalhes-livro4">Matemática Básica</a></td>
      <td>Didático</td>
      <td>Vários</td>
      <td>10</td>
      <td>04</td>
      <td>20/10/2024</td>
      <td><span class="status-badge ativo">Ativo</span></td>
    </tr>
  </tbody>
</table>

<!-- Botões principais para interações -->
<div class="botoes-container">
  <button class="botao-adicionar" id="botao-adicionar">Adicionar</button>
  <button class="botao-editar" id="botao-editar">Editar</button>
  <button class="botao-mudar" id="botao-mudar">Mudar status</button>
</div>

<!-- Modal para exibição de detalhes do livro -->
<div id="modal">
  <div id="modal-content">
    <span id="close-modal">&times;</span>
    <div id="modal-body"></div>
  </div>
</div>

<!-- Modal de Adicionar Livro -->
<div id="modal-adicionar" class="modal">
  <div class="modal-content">
    <h2>Adicionar Livro</h2>
    <form id="form-adicionar">
      
      <!-- Linha com ID gerado automaticamente e nome -->
      <div class="linha-dupla">
        <div>
          <label>ID do Livro (gerado):</label>
          <input type="text" id="livro-id" readonly>
        </div>
        <div>
          <label>Nome do Livro:</label>
          <input type="text" id="livro-nome" required>
        </div>
      </div>

      <!-- Linha com autor e gênero -->
      <div class="linha-dupla">
        <div>
          <label>Autor:</label>
          <input type="text" id="livro-autor" required>
        </div>
        <div>
          <label>Gênero:</label>
          <input type="text" id="livro-genero" required>
        </div>
      </div>

      <!-- Linha com quantidade e prateleira -->
      <div class="linha-dupla">
        <div>
          <label>Quantidade em Estoque:</label>
          <input type="number" id="livro-quantidade" required>
        </div>
        <div>
          <label>Prateleira:</label>
          <input type="number" id="livro-prateleira" required>
        </div>
      </div>

      <!-- Linha com data e status -->
      <div class="linha-dupla">
        <div>
          <label>Data de Cadastro (automática):</label>
          <input type="date" id="livro-data" readonly>
        </div>
        <div>
          <label>Status:</label>
          <select id="livro-status">
            <option value="Ativo">Ativo</option>
            <option value="Desativado">Desativado</option>
          </select>
        </div>
      </div>

      <!-- Linha com editora e data de lançamento -->
      <div class="linha-dupla">
        <div>
          <label>Editora:</label>
          <input type="text" id="livro-editora" required>
        </div>
        <div>
          <label>Data de Lançamento:</label>
          <input type="date" id="livro-lancamento" required>
        </div>
      </div>

      <!-- Linha única com sinopse -->
      <div class="linha-unica">
        <label>Sinopse:</label>
        <textarea id="livro-sinopse" rows="4" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #85ccea; background-color: #f0faff; margin-bottom: 15px;" required></textarea>
      </div>

<!-- Linha com imagem e prévia -->
<div class="linha-dupla">
  <div>
    <label for="livro-imagem-preview">Pré-visualização:</label>
    <img id="livro-imagem-preview" src="" alt="Prévia da imagem" />
  </div>
  <div>
    <label for="livro-imagem">Imagem do Livro:</label>
    <input type="file" id="livro-imagem" accept="image/*">
  </div>
</div>
      <!-- Botões do formulário -->
      <div style="text-align: center; margin-top: 15px;">
        <button type="submit" id="botao-salvar-adicionar">Salvar</button>
        <button type="button" id="botao-cancelar-adicionar">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal para mudar o status do livro -->
<div id="modal-status" class="modal" style="display: none;">
  <div class="modal-content">
    <h2>Mudar Status do Livro</h2>
    <form id="form-status">
      <p>Selecione o novo status:</p>
      <div class="linha-dupla-radio">
        <label>
          <input type="radio" name="novo-status" value="Ativo"> Ativo
        </label>
        <label>
          <input type="radio" name="novo-status" value="Desativado"> Desativado
        </label>
      </div>
      <div style="text-align: center; margin-top: 15px;">
        <button type="submit" id="salvar-status">Salvar</button>
        <button type="button" id="cancelar-status">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal de Edição de Livro -->
<div id="modal-editar" class="modal">
  <div class="modal-content">
    <h2>Editar Livro</h2>
    <form id="form-editar">
      
      <!-- Linha com ID e nome -->
      <div class="linha-dupla">
        <div>
          <label>ID do Livro (gerado):</label>
          <input type="text" id="editar-livro-id" readonly>
        </div>
        <div>
          <label>Nome do Livro:</label>
          <input type="text" id="editar-livro-nome" required>
        </div>
      </div>

      <!-- Linha com autor e gênero -->
      <div class="linha-dupla">
        <div>
          <label>Autor:</label>
          <input type="text" id="editar-livro-autor" required>
        </div>
        <div>
          <label>Gênero:</label>
          <input type="text" id="editar-livro-genero" required>
        </div>
      </div>

      <!-- Linha com quantidade e prateleira -->
      <div class="linha-dupla">
        <div>
          <label>Quantidade em Estoque:</label>
          <input type="number" id="editar-livro-quantidade" required>
        </div>
        <div>
          <label>Prateleira:</label>
          <input type="number" id="editar-livro-prateleira" required>
        </div>
      </div>

      <!-- Linha com data e status -->
      <div class="linha-dupla">
        <div>
          <label>Data de Cadastro (automática):</label>
          <input type="date" id="editar-livro-data" readonly>
        </div>
        <div>
          <label>Status:</label>
          <select id="editar-livro-status">
            <option value="Ativo">Ativo</option>
            <option value="Desativado">Desativado</option>
          </select>
        </div>
      </div>

      <!-- Linha com editora e data de lançamento -->
      <div class="linha-dupla">
        <div>
          <label>Editora:</label>
          <input type="text" id="editar-livro-editora" required>
        </div>
        <div>
          <label>Data de Lançamento:</label>
          <input type="date" id="editar-livro-lancamento" required>
        </div>
      </div>

      <!-- Linha única com sinopse -->
      <div class="linha-unica">
        <label>Sinopse:</label>
        <textarea id="editar-livro-sinopse" rows="4" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #85ccea; background-color: #f0faff; margin-bottom: 15px;" required></textarea>
      </div>

<!-- Linha com imagem e prévia -->
<div class="linha-dupla">
  <div>
    <label for="livro-imagem-preview">Pré-visualização:</label>
    <img id="imagem-preview-editar" src="" alt="Prévia da imagem" />
  </div>
  <div>
    <label for="livro-imagem-editar">Imagem do Livro:</label>
    <input type="file" id="livro-imagem-editar" accept="image/*">
  </div>
</div>

<div style="text-align: center; margin-top: 15px;">
  <button type="submit" id="salvar-editar">Salvar</button>
  <button type="button" id="cancelar-editar">Cancelar</button>
</div>
</form>
</div>
</div>

<!-- Script com funcionalidades da página -->
<script src="subtelas_javascript/controleestoque.js"></script>
</body>
</html>