<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Relatórios - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/registro_relatorio.css"/>
</head>
<body>

  <!-- Cabeçalho da página -->
    <header> 
        <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
        <h1> RELATÓRIOS </h1>
    </header>

  <!-- Título de seção -->
  <h2>Informações necessárias:</h2>

  <div class="container">
    <!-- Formulário para preenchimento dos dados -->
    <form class="formulario" id="form_pessoal" action="#" method="post">
      
      <!-- Linha de entrada para ID do Funcionário e Nome -->
      <div class="linha-dupla">
        <!-- Campo para ID do Funcionário -->
        <div class="input-group">
          <p>ID do funcionário:</p>
          <span class="icon">#</span>
          <input type="number" name="id" id="id_funcionario" required oninput="preencherNomeFuncionario()">
        </div>

        <!-- Campo para Nome do Funcionário (apenas leitura) -->
        <div class="input-group">
          <p>Nome do funcionário:</p>
          <span class="icon">👤</span>
          <input type="text" name="nome_funcionario" id="nome_funcionario" readonly>
        </div>
      </div>

      <!-- Linha de entrada para Nome do Relatório e Data -->
      <div class="linha-dupla">
        <!-- Campo para Nome do Relatório -->
        <div class="input-group">
          <p>Nome do relatório:</p>
          <span class="icon">🧾</span>
          <input type="text" name="nome_relatorio" required>
        </div>

        <!-- Campo para Data (preenchido com a data de hoje) -->
        <div class="input-group">
          <p>Data:</p>
          <span class="icon">📅</span>
          <input type="date" name="data" id="data" required min="" max="">
        </div>
      </div>

      <!-- Linha de entrada para o Arquivo -->
      <div class="input-group arquivo">
        <p>
          Arquivo: (PDF, WORD E EXCEL)
          <span class="icon">📂</span>
        </p>
        <!-- Exibição do nome do arquivo selecionado -->
        <input type="text" name="arquivo" id="arquivo" readonly placeholder="Nenhum arquivo selecionado">
        <!-- Campo de seleção de arquivo -->
        <input type="file" id="seletor_arquivo" accept=".pdf, .doc, .docx, .xls, .xlsx" style="display: none;" multiple onchange="atualizarNomeArquivo()">
      </div>
      
      <!-- Botão para abrir o seletor de arquivos -->
      <button type="button" class="botao-inline" onclick="document.getElementById('seletor_arquivo').click()">Selecionar Arquivo</button>

      <!-- Linha de botões de Enviar e Cancelar -->
      <div class="linha-dupla">
        <!-- Botão de Enviar -->
        <div class="botao-wrapper">
          <button class="botao-enviar" id="botao-enviar">Enviar</button>
        </div>

      <!-- Botão de Cancelar -->
      <div class="botao-wrapper">
        <button type="button" class="botao-cancelar" id="botao-cancelar" onclick="document.getElementById('form_pessoal').reset(); document.getElementById('arquivo').value = '';"> Cancelar </button>
      </div>
      </div>
    </form>
  </div>

  <!-- Importação da biblioteca de Alertas (SweetAlert) -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    // Função para atualizar o nome do arquivo selecionado
    function atualizarNomeArquivo() {
      const inputArquivo = document.getElementById('seletor_arquivo');
      const nomeArquivo = document.getElementById('arquivo');
      const listaArquivos = document.getElementById('lista-arquivos');
      const arquivoBox = document.getElementById('arquivo-box');
      const arquivosSelecionados = inputArquivo.files;

      // Verifica se há arquivos selecionados
      if (arquivosSelecionados.length > 0) {
        // Exibe o nome do primeiro arquivo selecionado
        nomeArquivo.value = arquivosSelecionados[0].name;
        arquivoBox.style.display = 'block';
        listaArquivos.innerHTML = '';
        
        // Lista os arquivos selecionados
        for (let i = 0; i < arquivosSelecionados.length; i++) {
          const li = document.createElement('li');
          li.textContent = arquivosSelecionados[i].name;
          listaArquivos.appendChild(li);
        }
      } else {
        // Caso nenhum arquivo seja selecionado
        nomeArquivo.value = 'Nenhum arquivo selecionado';
        arquivoBox.style.display = 'none';
      }
    }


    document.getElementById('botao-enviar').addEventListener('click', function (e) {
  e.preventDefault(); // Impede o envio padrão do formulário

  const form = document.getElementById('form_login');
  const inputs = form.querySelectorAll('input[required]');
  const arquivoInput = document.getElementById('seletor_arquivo');

  let todosPreenchidos = true;

  inputs.forEach(input => {
    if (!input.value.trim()) {
      todosPreenchidos = false;
    }
  });

  if (todosPreenchidos && arquivoInput.files.length > 0) {
    Swal.fire({
      title: 'Sucesso!',
      text: 'Relatório enviado com sucesso!',
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#ffbcfc'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit(); // Submete o formulário após confirmação
      }
    });
  } else {
    Swal.fire({
      title: 'Erro!',
      text: 'Preencha todos os campos e selecione um arquivo.',
      icon: 'error',
      confirmButtonText: 'OK',
      confirmButtonColor: '#ffbcfc'
    });
  }
});

  </script>
</body>
  <script src="subtelas_javascript/registro_relatorios.js"></script>
</html>
