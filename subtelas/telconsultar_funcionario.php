<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/telconsultar_func.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <header>
        <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
        <h1>Consultar Funcionários</h1>
    </header>

  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" name="nome_funcionario" placeholder="Buscar funcionario..." required>
    </div>
  </div>
  
  <nav>
    <table id="funcionarios-table" border="1">
      <thead>
        <tr>
          <th>ID</th>
          <th>NOME COMPLETO</th>
          <th>CARGO</th>
          <th>DATA DE NASCIMENTO</th>
          <th>DATA EFETIVAÇÃO</th>
          <th>STATUS</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>0001</td>
          <td><a href="#" class="detalhes-funcionarios">Silvio Luis de Sousa</a></td>
          <td>Gerente</td>
          <td>21/03/1965</td>
          <td>08/07/2024</td>
          <td><span class="status-badge ativo">Ativo</span></td>
        </tr>
        <tr>
          <td>0002</td>
          <td><a href="#" class="detalhes-funcionarios2">Maria da Graça Xuxa Meneghel</a></td>
          <td>Recreador</td>
          <td>27/03/1963</td>
          <td>12/12/2024</td>
          <td><span class="status-badge ativo">Ativo</span></td>
        </tr>
        <tr>
          <td>0003</td>
          <td><a href="#" class="detalhes-funcionarios3">Ruan de Mello Vieira</a></td>
          <td>Bibliotecário</td>
          <td>03/07/2007</td>
          <td>09/06/2025</td>
          <td><span class="status-badge desativado">Desativado</span></td>
        </tr>
        <tr>
          <td>0004</td>
          <td><a href="#" class="detalhes-funcionarios4">Marcos Paulo Fernandes</a></td>
          <td>Repositor</td>
          <td>31/10/2008</td>
          <td>16/06/2025</td>
          <td><span class="status-badge ativo">Ativo</span></td>
        </tr>
        <tr>
           <td>0005</td>
           <td><a href="#" class="detalhes-funcionarios5">Gerard Arthur Way</a></td>
           <td>Recreador</td>
           <td>09/04/1977</td>
           <td>05/02/2025</td>
           <td><span class="status-badge desativado">Desativado</span></td>
        </tr>
        <tr>
           <td>0006</td>
           <td><a href="#" class="detalhes-funcionarios6">Kim Sunoo</a></td>
           <td>Repositor</td>
           <td>24/06/2003</td>
           <td>08/10/2024</td>
           <td><span class="status-badge desativado">Desativado</span></td>
        </tr>
        <tr>
           <td>0007</td>
           <td><a href="#" class="detalhes-funcionarios7">Dwayne Douglas Johnson</a></td>
           <td>Recreador</td>
           <td>02/05/1972</td>
           <td>29/09/2024</td>   
           <td><span class="status-badge ativo">Ativo</span></td>
        </tr>
        <tr>
           <td>0008</td>
           <td><a href="#" class="detalhes-funcionarios8">Mason Thames</a></td>
           <td>Recreador</td>
           <td>10/07/2007</td>
           <td>11/03/2025</td>
           <td><span class="status-badge ativo">Ativo</span></td>
        </tr>
        <tr>
           <td>0009</td>
           <td><a href="#" class="detalhes-funcionarios9">Taylor Lautner</a></td>
           <td>Bibliotecário</td>
           <td>19/12/1980</td>
           <td>17/08/2024</td>
           <td><span class="status-badge ativo">Ativo</span></td>
        </tr>
        <tr>
           <td>0010</td>
           <td><a href="#" class="detalhes-funcionarios10">James Hetfield</a></td>
           <td>Gestor</td>
           <td>03/08/1963</td>
           <td>21/08/2024</td>
           <td><span class="status-badge desativado">Desativado</span></td>
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

  <!-- Modal de Edição -->
  <div id="modal-editar" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Editar Funcionário</h2>
      <form id="form-editar">
        <div class="linha-dupla">
          <div>
            <label>Código:</label>
            <input type="text" id="funcionario-codigo" readonly>
          </div>
          <div>
            <label>Nome:</label>
            <input type="text" id="funcionario-nome" required>
          </div>
        </div>

        <div class="linha-dupla">
          <div>
            <label>CPF:</label>
            <input type="text" id="funcionario-cpf" required>
          </div>
          <div>
            <label>Sexo:</label>
            <select id="funcionario-sexo" required>
              <option value="Masculino">Masculino</option>
              <option value="Feminino">Feminino</option>
            </select>
          </div>
        </div>

        <div class="linha-dupla">
          <div>
            <label>Estado Civil:</label>
            <select id="funcionario-civil" required>
              <option value="Solteiro">Solteiro</option>
              <option value="Casado">Casado</option>
              <option value="Divorciado">Divorciado</option>
              <option value="Viúvo">Viúvo</option>
            </select>
          </div>
          <div>
            <label>Cargo:</label>
            <input type="text" id="funcionario-cargo" required>
          </div>
        </div>

        <div class="linha-dupla">
          <div>
            <label>Data de Nascimento:</label>
            <input type="date" id="funcionario-nascimento" required>
          </div>
          <div>
            <label>Data de Efetivação:</label>
            <input type="date" id="funcionario-efetivacao" required>
          </div>
        </div>

        <div class="linha-dupla">
          <div>
            <label>Estado:</label>
            <input type="text" id="funcionario-estado" required>
          </div>
          <div>
            <label>Cidade:</label>
            <input type="text" id="funcionario-cidade" required>
          </div>
        </div>

        <div class="linha-dupla">
          <div>
            <label>Bairro:</label>
            <input type="text" id="funcionario-bairro" required>
          </div>
          <div>
            <label>Rua:</label>
            <input type="text" id="funcionario-rua" required>
          </div>
        </div>

        <div class="linha-dupla">
          <div>
            <label>Número:</label>
            <input type="text" id="funcionario-numero" required>
          </div>
          <div>
            <label>Telefone:</label>
            <input type="text" id="funcionario-telefone" required>
          </div>
        </div>

        <div class="botao">
          <button type="submit" class="btn" id="btn-salvar">Salvar</button>
          <button type="submit" class="btn" id="cancelar-edicao">Cancelar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Verificar se o SweetAlert2 foi carregado
    window.addEventListener('load', function() {
      if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 não foi carregado corretamente');
      } else {
        console.log('SweetAlert2 carregado com sucesso');
      }
    });
  </script>
  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
</body>
</html>
