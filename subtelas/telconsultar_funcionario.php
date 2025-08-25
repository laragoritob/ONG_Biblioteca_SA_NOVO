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
        <h1>Consultar Funcionários</h1>
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
          <th>ID</th>
          <th>NOME COMPLETO</th>
          <th>CARGO</th>
          <th>DATA DE NASCIMENTO</th>
          <th>DATA EFETIVAÇÃO</th>
          <th>STATUS</th>
          <th>AÇÕES</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>0001</td>
          <td><a href="#" class="detalhes-funcionarios">Silvio Luis de Sousa</a></td>
          <td>Gerente</td>
          <td>21/03/1965</td>
          <td>08/07/2024</td>
          <td><span class="status-badge status-ativo">Ativo</span></td>
          <td>
            <button class="btn-action" onclick="editarFuncionario('0001', 'Silvio Luis de Sousa', 'Gerente', '1965-03-21', '2024-07-08')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button class="btn-action btn-delete" onclick="excluirFuncionario('0001', 'Silvio Luis de Sousa')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
            </button>
          </td>
        </tr>
        <tr>
          <td>0002</td>
          <td><a href="#" class="detalhes-funcionarios2">Maria da Graça Xuxa Meneghel</a></td>
          <td>Recreador</td>
          <td>27/03/1963</td>
          <td>12/12/2024</td>
          <td><span class="status-badge status-ativo">Ativo</span></td>
          <td>
            <button class="btn-action" onclick="editarFuncionario('0002', 'Maria da Graça Xuxa Meneghel', 'Recreador', '1963-03-27', '2024-12-12')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button class="btn-action btn-delete" onclick="excluirFuncionario('0002', 'Maria da Graça Xuxa Meneghel')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
            </button>
          </td>
        </tr>
        <tr>
          <td>0003</td>
          <td><a href="#" class="detalhes-funcionarios3">Ruan de Mello Vieira</a></td>
          <td>Bibliotecário</td>
          <td>03/07/2007</td>
          <td>09/06/2025</td>
          <td><span class="status-badge status-inativo">Inativo</span></td>
          <td>
            <button class="btn-action" onclick="editarFuncionario('0003', 'Ruan de Mello Vieira', 'Bibliotecário', '2007-07-03', '2025-06-09')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button class="btn-action btn-delete" onclick="excluirFuncionario('0003', 'Ruan de Mello Vieira')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
            </button>
          </td>
        </tr>
        <tr>
          <td>0004</td>
          <td><a href="#" class="detalhes-funcionarios4">Marcos Paulo Fernandes</a></td>
          <td>Repositor</td>
          <td>31/10/2008</td>
          <td>16/06/2025</td>
          <td><span class="status-badge status-ativo">Ativo</span></td>
          <td>
            <button class="btn-action" onclick="editarFuncionario('0004', 'Marcos Paulo Fernandes', 'Repositor', '2008-10-31', '2025-06-16')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
              </svg>
            </button>
            <button class="btn-action btn-delete" onclick="excluirFuncionario('0004', 'Marcos Paulo Fernandes')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 6h18"></path>
                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
              </svg>
            </button>
          </td>
        </tr>
        <tr>
           <td>0005</td>
           <td><a href="#" class="detalhes-funcionarios5">Gerard Arthur Way</a></td>
           <td>Recreador</td>
           <td>09/04/1977</td>
           <td>05/02/2025</td>
           <td><span class="status-badge status-inativo">Inativo</span></td>
           <td>
             <button class="btn-action" onclick="editarFuncionario('0005', 'Gerard Arthur Way', 'Recreador', '1977-04-09', '2025-02-05')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                 <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
               </svg>
             </button>
             <button class="btn-action btn-delete" onclick="excluirFuncionario('0005', 'Gerard Arthur Way')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 6h18"></path>
                 <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                 <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
               </svg>
             </button>
           </td>
        </tr>
        <tr>
           <td>0006</td>
           <td><a href="#" class="detalhes-funcionarios6">Kim Sunoo</a></td>
           <td>Repositor</td>
           <td>24/06/2003</td>
           <td>08/10/2024</td>
           <td><span class="status-badge status-inativo">Inativo</span></td>
           <td>
             <button class="btn-action" onclick="editarFuncionario('0006', 'Kim Sunoo', 'Repositor', '2003-06-24', '2024-10-08')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                 <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
               </svg>
             </button>
             <button class="btn-action btn-delete" onclick="excluirFuncionario('0006', 'Kim Sunoo')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 6h18"></path>
                 <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                 <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
               </svg>
             </button>
           </td>
        </tr>
        <tr>
           <td>0007</td>
           <td><a href="#" class="detalhes-funcionarios7">Dwayne Douglas Johnson</a></td>
           <td>Recreador</td>
           <td>02/05/1972</td>
           <td>29/09/2024</td>   
           <td><span class="status-badge status-ativo">Ativo</span></td>
           <td>
             <button class="btn-action" onclick="editarFuncionario('0007', 'Dwayne Douglas Johnson', 'Recreador', '1972-05-02', '2024-09-29')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                 <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
               </svg>
             </button>
             <button class="btn-action btn-delete" onclick="excluirFuncionario('0007', 'Dwayne Douglas Johnson')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 6h18"></path>
                 <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                 <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
               </svg>
             </button>
           </td>
        </tr>
        <tr>
           <td>0008</td>
           <td><a href="#" class="detalhes-funcionarios8">Mason Thames</a></td>
           <td>Recreador</td>
           <td>10/07/2007</td>
           <td>11/03/2025</td>
           <td><span class="status-badge status-ativo">Ativo</span></td>
           <td>
             <button class="btn-action" onclick="editarFuncionario('0008', 'Mason Thames', 'Recreador', '2007-07-10', '2025-03-11')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                 <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
               </svg>
             </button>
             <button class="btn-action btn-delete" onclick="excluirFuncionario('0008', 'Mason Thames')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 6h18"></path>
                 <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                 <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
               </svg>
             </button>
           </td>
        </tr>
        <tr>
           <td>0009</td>
           <td><a href="#" class="detalhes-funcionarios9">Taylor Lautner</a></td>
           <td>Bibliotecário</td>
           <td>19/12/1980</td>
           <td>17/08/2024</td>
           <td><span class="status-badge status-ativo">Ativo</span></td>
           <td>
             <button class="btn-action" onclick="editarFuncionario('0009', 'Taylor Lautner', 'Bibliotecário', '1980-12-19', '2024-08-17')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                 <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
               </svg>
             </button>
             <button class="btn-action btn-delete" onclick="excluirFuncionario('0009', 'Taylor Lautner')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 6h18"></path>
                 <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                 <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
               </svg>
             </button>
           </td>
        </tr>
        <tr>
           <td>0010</td>
           <td><a href="#" class="detalhes-funcionarios10">James Hetfield</a></td>
           <td>Gestor</td>
           <td>03/08/1963</td>
           <td>21/08/2024</td>
           <td><span class="status-badge status-inativo">Inativo</span></td>
           <td>
             <button class="btn-action" onclick="editarFuncionario('0010', 'James Hetfield', 'Gestor', '1963-08-03', '2024-08-21')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                 <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
               </svg>
             </button>
             <button class="btn-action btn-delete" onclick="excluirFuncionario('0010', 'James Hetfield')">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                 <path d="M3 6h18"></path>
                 <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                 <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
               </svg>
             </button>
           </td>
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
  <script src="subtelas_javascript/telconsultar_funcionarios.js"></script>
</body>
</html>
