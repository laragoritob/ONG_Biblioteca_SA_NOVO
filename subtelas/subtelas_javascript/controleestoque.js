document.addEventListener("DOMContentLoaded", function () {
  // Referências gerais
  const modal = document.getElementById('modal');
  const modalBody = document.getElementById('modal-body');
  const closeModal = document.getElementById('close-modal');
  const botaoAdicionar = document.getElementById("botao-adicionar");
  const modalAdicionar = document.getElementById("modal-adicionar");
  const livroIdInput = document.getElementById("livro-id");
  const formAdicionar = document.getElementById("form-adicionar");

  // Função para abrir o modal com o conteúdo do livro
  function abrirModal(conteudo) {
    modalBody.innerHTML = conteudo;
    modal.style.display = 'block';
  }

  // Conteúdos HTML para os livros existentes
  const conteudoLivro1 = `
    <img src="subtelas_img/paratodososgarotosquejaamei.jpg" title="Livro" class="hmlivro" />
    <h3 style="margin-top: 0;">Para Todos Os Garotos Que Já Amei</h3>
    <p>Lara Jean guarda suas cartas de amor em uma caixa azul-petróleo que ganhou da mãe. Não são cartas que ela recebeu de alguém, mas que ela mesma escreveu. Uma para cada garoto que amou — cinco ao todo. São cartas sinceras, sem joguinhos nem fingimentos, repletas de coisas que Lara Jean não diria a ninguém, confissões de seus sentimentos mais profundos.</p>
    <p>Até que um dia essas cartas secretas são misteriosamente enviadas aos destinatários, e de uma hora para outra a vida amorosa de Lara Jean sai do papel e se transforma em algo que ela não pode mais controlar.</p><br><br><br>
    <ul>
      <li><strong>Autora:</strong> Jenny Han</li>
      <li><strong>Gêneros:</strong> Ficção juvenil, Romance de amor</li>
      <li><strong>Editora:</strong> Simon & Schuster</li>
      <li><strong>Lançamento:</strong> 15/04/2014</li>
    </ul>
  `;

  const conteudoLivro2 = `
    <img src="subtelas_img/ocortico.jpg" title="Livro" class="hmlivro" />
    <h3 style="margin-top: 0;">O Cortiço</h3>
    <p>Pobreza, corrupção, injustiça, traição são elementos que integram O cortiço, principal obra do Naturalismo brasileiro. Nela, Aluísio Azevedo denuncia as mazelas sociais enfrentadas pelos moradores de um cortiço no Rio de Janeiro no século XIX. É um romance que convida a analisar por meio da observação crítica do cotidiano das personagens a animalização do ser humano, questão que se mostra, mais do que nunca, atual.</p><br><br><br><br><br><br>
    <ul>
      <li><strong>Autor:</strong> Aluísio Azevedo</li>
      <li><strong>Gêneros:</strong> Naturalismo</li>
      <li><strong>Editora:</strong> Editoras variadas</li>
      <li><strong>Lançamento:</strong> 01/01/1890</li>
    </ul>
  `;

  const conteudoLivro3 = `
    <img src="subtelas_img/opequenoprincipe.jpg" title="Livro" class="hmlivro" />
    <h3>O Pequeno Príncipe</h3>
    <p>Nesta história que marcou gerações de leitores em todo o mundo, um piloto cai com seu avião no deserto do Saara e encontra um pequeno príncipe, que o leva a uma aventura filosófica e poética através de planetas que encerram a solidão humana.<br><br><br>
    Um livro para todos os públicos, O pequeno príncipe é uma obra atemporal, com metáforas pertinentes e aprendizados sobre afeto, sonhos, esperança e tudo aquilo que é invisível aos olhos.</p><br><br><br><br><br>
    <ul>
      <li><strong>Autor:</strong> Antoine de Saint-Exupéry</li>
      <li><strong>Gênero:</strong> Fábula, Literatura Infantil</li>
      <li><strong>Editora:</strong> Gallimard</li>
      <li><strong>Lançamento:</strong> 01/04/1943</li>
    </ul>
  `;

  const conteudoLivro4 = `
    <img src="subtelas_img/olivrodamatematica.jpg" title="Livro" class="hmlivro" />
    <h3>O Livro da Matemática</h3>
    <p>O livro da matemática está repleto de explicações concisas, sem jargões, que descomplicam teorias complexas e citações que facilitam a visualização e memorização dos conceitos, além de ilustrações que complementam e brincam com nossa compreensão dos números.</p><br><br><br><br><br><br><br><br><br><br>
    <ul>
      <li><strong>Autor:</strong> Vários</li>
      <li><strong>Editora:</strong> Globo Livros</li>
      <li><strong>Gênero:</strong> Didático</li>
      <li><strong>Lançamento:</strong> 19/10/2020</li>
    </ul>
  `;

  // Eventos para abrir modal com detalhes dos livros existentes
  document.querySelector('.detalhes-livro').addEventListener('click', function(e) {
    e.preventDefault();
    abrirModal(conteudoLivro1);
  });

  document.querySelector('.detalhes-livro2').addEventListener('click', function(e) {
    e.preventDefault();
    abrirModal(conteudoLivro2);
  });

  document.querySelector('.detalhes-livro3').addEventListener('click', function(e) {
    e.preventDefault();
    abrirModal(conteudoLivro3);
  });

  document.querySelector('.detalhes-livro4').addEventListener('click', function(e) {
    e.preventDefault();
    abrirModal(conteudoLivro4);
  });

  // Função para adicionar evento de clique aos links
  function adicionarEventosLinks() {
    // Para livros existentes
    document.querySelectorAll('.detalhes-livro').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        abrirModal(conteudoLivro1);
      });
    });

    document.querySelectorAll('.detalhes-livro2').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        abrirModal(conteudoLivro2);
      });
    });

    document.querySelectorAll('.detalhes-livro3').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        abrirModal(conteudoLivro3);
      });
    });

    document.querySelectorAll('.detalhes-livro4').forEach(link => {
      link.addEventListener('click', e => {
        e.preventDefault();
        abrirModal(conteudoLivro4);
      });
    });

    // Para livros novos
    document.querySelectorAll('.detalhes-livro-novo').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const linha = this.closest('tr');
        if (linha) {
          const conteudoSalvo = linha.getAttribute('data-modal-content');
          if (conteudoSalvo) {
            abrirModal(conteudoSalvo);
          }
        }
      });
    });
  }

  // Adicionar eventos iniciais
  adicionarEventosLinks();

  // Fechar modal principal
  closeModal.onclick = function () {
    modal.style.display = 'none';
  };

  window.addEventListener("click", function (event) {
    if (event.target === modal) {
      modal.style.display = "none";
    }
  });

  // Função para gerar ID aleatório de livro
  let contador = 5;
  function gerarIdLivro() {
    const id = `#${String(contador).padStart(4, '0')}`;
    contador++;
    return id;
  }

  // Abrir modal de adicionar livro
  if (botaoAdicionar && modalAdicionar && livroIdInput) {
    botaoAdicionar.addEventListener("click", () => {
      modalAdicionar.style.display = "block";
      livroIdInput.value = gerarIdLivro();
      
      // Definir a data atual
      const hoje = new Date();
      const ano = hoje.getFullYear();
      const mes = String(hoje.getMonth() + 1).padStart(2, '0');
      const dia = String(hoje.getDate()).padStart(2, '0');
      const dataHoje = `${ano}-${mes}-${dia}`;
      
      const inputData = document.getElementById("livro-data");
      if (inputData) {
        inputData.value = dataHoje;
        inputData.readOnly = true;
      }
    });
  }

  // Adicionar novo livro
  if (formAdicionar) {
    formAdicionar.addEventListener("submit", function(e) {
      e.preventDefault();

      const inputs = this.querySelectorAll('input[required], textarea[required]');
      let todosPreenchidos = true;

      inputs.forEach(input => {
        if (!input.value.trim()) {
          todosPreenchidos = false;
        }
      });

      if (!todosPreenchidos) {
        Swal.fire({
          title: 'Erro!',
          text: 'Por favor, preencha todos os campos obrigatórios.',
          icon: 'error',
          confirmButtonText: 'OK',
          confirmButtonColor: '#f44336'
        });
        return;
      }

      const tabela = document.querySelector("#livros-table tbody");
      const novaLinha = tabela.insertRow();

      // Formatar datas
      const dataInput = document.getElementById("livro-data").value;
      const [ano, mes, dia] = dataInput.split("-");
      const dataFormatada = `${dia}/${mes}/${ano}`;

      const lancamentoInput = document.getElementById("livro-lancamento").value;
      const [anoLanc, mesLanc, diaLanc] = lancamentoInput.split("-");
      const lancamentoFormatado = `${diaLanc}/${mesLanc}/${anoLanc}`;

      // Coletar dados do formulário
      const livroId = document.getElementById("livro-id").value;
      const livroNome = document.getElementById("livro-nome").value;
      const livroGenero = document.getElementById("livro-genero").value;
      const livroAutor = document.getElementById("livro-autor").value;
      const livroQuantidade = document.getElementById("livro-quantidade").value;
      const livroPrateleira = document.getElementById("livro-prateleira").value;
      const livroStatus = document.getElementById("livro-status").value || 'Ativo';
      const livroEditora = document.getElementById("livro-editora").value;
      const livroSinopse = document.getElementById("livro-sinopse").value;

      // Processar imagem
      const inputImagem = document.getElementById("livro-imagem");
      let imagemSrc = "subtelas_img/default-book.jpg";
      if (inputImagem.files && inputImagem.files[0]) {
        imagemSrc = URL.createObjectURL(inputImagem.files[0]);
      }

      // Criar conteúdo do modal
      const conteudoNovoLivro = `
        <img src="${imagemSrc}" title="Livro" class="hmlivro" />
        <h3 style="margin-top: 0;">${livroNome}</h3>
        <p>${livroSinopse}</p><br><br><br>
        <ul>
          <li><strong>Autor:</strong> ${livroAutor}</li>
          <li><strong>Gêneros:</strong> ${livroGenero}</li>
          <li><strong>Editora:</strong> ${livroEditora}</li>
          <li><strong>Lançamento:</strong> ${lancamentoFormatado}</li>
        </ul>
      `;

      // Adicionar linha na tabela
      novaLinha.setAttribute('data-modal-content', conteudoNovoLivro);
      novaLinha.innerHTML = `
        <td>${livroId}</td>
        <td><a href="#" class="detalhes-livro-novo">${livroNome}</a></td>
        <td>${livroGenero}</td>
        <td>${livroAutor}</td>
        <td>${livroQuantidade}</td>
        <td>${livroPrateleira}</td>
        <td>${dataFormatada}</td>
        <td><span class="status-badge ${livroStatus.toLowerCase()}">${livroStatus}</span></td>
      `;

      // Recarregar eventos dos links
      adicionarEventosLinks();

      // Mostrar mensagem de sucesso
      Swal.fire({
        title: 'Sucesso!',
        text: 'Livro cadastrado com sucesso!',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#4CAF50'
      }).then(() => {
        // Limpar e fechar
        modalAdicionar.style.display = "none";
        formAdicionar.reset();
        if (document.getElementById("livro-imagem-preview")) {
          document.getElementById("livro-imagem-preview").src = "";
        }
      });
    });
  }

  // Validação do formulário usando SweetAlert2
  formAdicionar.addEventListener('submit', function (e) {
    e.preventDefault();

    const inputs = this.querySelectorAll('input[required]');
    let todosPreenchidos = true;

    inputs.forEach(input => {
      if (!input.value.trim()) {
        todosPreenchidos = false;
      }
    });

    if (todosPreenchidos) {
      Swal.fire({
        title: 'Sucesso!',
        text: 'Livro cadastrado com sucesso!',
        icon: 'success',
        confirmButtonText: 'OK',
        confirmButtonColor: '#4CAF50'
      }).then(() => {
        this.reset();
      });
    } else {
      Swal.fire({
        title: 'Erro!',
        text: 'Por favor, preencha todos os campos obrigatórios.',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#f44336'
      });
    }
  });

  // Botão cancelar adicionar
  document.getElementById('botao-cancelar-adicionar').addEventListener('click', () => {
    Swal.fire({
      title: 'Cancelado!',
      text: 'Cadastro cancelado.',
      icon: 'error',
      confirmButtonText: 'OK',
      confirmButtonColor: '#ff9800'
    }).then((result) => {
      if (result.isConfirmed) {
        formAdicionar.reset();
        modalAdicionar.style.display = 'none';
      }
    });
  });

  // Mudar Status
  const botaoMudar = document.getElementById('botao-mudar');
  const modalStatus = document.getElementById("modal-status");
  const cancelarStatus = document.getElementById("cancelar-status");
  const formStatus = document.getElementById("form-status");
  let linhaSelecionadaStatus = null;

  botaoMudar.addEventListener("click", () => {
    Swal.fire({
      title: 'Digite o ID do livro',
      input: 'text',
      inputLabel: 'ID do livro (ex: 0001)',
      inputPlaceholder: 'Insira o ID aqui',
      showCancelButton: true,
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#4CAF50',
      cancelButtonColor: '#f44336',
      allowOutsideClick: false,
      allowEscapeKey: false,
      customClass: {
        title: 'titulo-id-livro'
      },
      inputValidator: (value) => {
        if (!value.trim()) {
          return 'Você precisa digitar um ID!';
        }
      }
    }).then((result) => {
      if (!result.isConfirmed) {
        // Adicionar animação ao cancelar
        const cancelButton = document.querySelector('.swal2-cancel');
        if (cancelButton) {
          cancelButton.classList.add('cancelado');
          setTimeout(() => {
            cancelButton.classList.remove('cancelado');
          }, 500);
        }
        return;
      }
      
      let idDigitado = result.value.trim();
      if (!idDigitado.startsWith('#')) {
        idDigitado = `#${idDigitado}`;
      }
      
      linhaSelecionadaStatus = null;
      const linhasTabela = document.querySelectorAll("#livros-table tbody tr");
  
      linhasTabela.forEach((linha) => {
        const idCelula = linha.querySelector("td").textContent.trim();
        if (idCelula === idDigitado) {
          linhaSelecionadaStatus = linha;
        }
      });

      if (!linhaSelecionadaStatus) {
        Swal.fire({
          icon: 'error',
          title: 'Livro não encontrado',
          text: `Nenhum livro com ID ${idDigitado} foi encontrado.`,
          confirmButtonText: 'OK',
          confirmButtonColor: '#f44336'
        });
      } else {
        modalStatus.style.display = "flex";
        modalStatus.setAttribute("data-id", idDigitado);
      }
    });
  });

  // Submissão do formulário de mudança de status
  formStatus.addEventListener("submit", function (e) {
    e.preventDefault();

    const statusSelecionado = formStatus.querySelector('input[name="novo-status"]:checked');

    if (!statusSelecionado) {
      Swal.fire({
        title: 'Erro!',
        text: 'Por favor, selecione um status.',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#f44336'
      });
      return;
    }

    if (!linhaSelecionadaStatus) {
      Swal.fire({
        icon: 'error',
        title: 'Erro',
        text: 'Nenhuma linha selecionada para alteração!',
        confirmButtonText: 'OK'
      });
      return;
    }

    const novoStatus = statusSelecionado.value;
    const nomeLivro = linhaSelecionadaStatus.cells[1].textContent;
    const idLivro = linhaSelecionadaStatus.cells[0].textContent;

    // Atualizar o status na tabela com o badge
    linhaSelecionadaStatus.cells[7].innerHTML = `<span class="status-badge ${novoStatus.toLowerCase()}">${novoStatus}</span>`;

    // Atualizar o botão no modal se estiver aberto
    const modalBody = document.getElementById('modal-body');
    if (modalBody) {
      const botaoStatus = modalBody.querySelector('.btn.desativar, .btn.ativar');
      if (botaoStatus) {
        botaoStatus.textContent = novoStatus === 'Ativo' ? 'DESATIVAR' : 'ATIVAR';
        botaoStatus.className = `btn ${novoStatus === 'Ativo' ? 'desativar' : 'ativar'}`;
        botaoStatus.onclick = () => alterarStatus(idLivro);
      }
    }

    modalStatus.style.display = "none";
    formStatus.reset();

    Swal.fire({
      title: 'Sucesso!',
      text: `Status de "${nomeLivro}" alterado para "${novoStatus}".`,
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#4CAF50',
      allowOutsideClick: false
    });
  });

  // Cancelar mudança de status
  cancelarStatus.addEventListener("click", function () {
    Swal.fire({
      title: 'Cancelado!',
      text: 'Alteração de status cancelada.',
      icon: 'error',
      confirmButtonText: 'OK',
      confirmButtonColor: '#ff9800'
    }).then(() => {
      formStatus.reset();
      modalStatus.style.display = "none";
    });
  });

  // Editar
  const botaoEditar = document.getElementById("botao-editar");
  const modalEditar = document.getElementById("modal-editar");
  const formEditar = document.getElementById("form-editar");
  const cancelarEditar = document.getElementById("cancelar-editar");
  let linhaSelecionadaEditar = null;

  botaoEditar.addEventListener("click", () => {
    Swal.fire({
      title: 'Digite o ID do livro',
      input: 'text',
      inputLabel: 'ID do livro (ex: 0001)',
      inputPlaceholder: 'Insira o ID aqui',
      showCancelButton: true,
      confirmButtonText: 'Confirmar',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: '#4CAF50',
      cancelButtonColor: '#f44336',
      allowOutsideClick: false,
      allowEscapeKey: false,
      inputValidator: (value) => {
        if (!value.trim()) return 'Você precisa digitar um ID!';
      }
    }).then((result) => {
      if (!result.isConfirmed) {
        // Adicionar animação ao cancelar
        const cancelButton = document.querySelector('.swal2-cancel');
        if (cancelButton) {
          cancelButton.classList.add('cancelado');
          setTimeout(() => {
            cancelButton.classList.remove('cancelado');
          }, 500);
        }
        return;
      }
      
      let idDigitado = result.value.trim();
      if (!idDigitado.startsWith('#')) {
        idDigitado = `#${idDigitado}`;
      }
      
      linhaSelecionadaEditar = null;
      const linhasTabela = document.querySelectorAll("#livros-table tbody tr");
  
      linhasTabela.forEach((linha) => {
        const idCelula = linha.querySelector("td").textContent.trim();
        if (idCelula === idDigitado) {
          linhaSelecionadaEditar = linha;
        }
      });

      if (!linhaSelecionadaEditar) {
        Swal.fire({
          icon: 'error',
          title: 'Livro não encontrado',
          text: `Nenhum livro com ID ${idDigitado} foi encontrado.`,
          confirmButtonText: 'OK',
          confirmButtonColor: '#f44336'
        });
      } else {
        // Preencher o modal de adicionar com os dados do livro
        document.getElementById("livro-id").value = linhaSelecionadaEditar.cells[0].textContent.trim();
        document.getElementById("livro-nome").value = linhaSelecionadaEditar.cells[1].textContent.trim();
        document.getElementById("livro-genero").value = linhaSelecionadaEditar.cells[2].textContent.trim();
        document.getElementById("livro-autor").value = linhaSelecionadaEditar.cells[3].textContent.trim();
        document.getElementById("livro-quantidade").value = linhaSelecionadaEditar.cells[4].textContent.trim();
        document.getElementById("livro-prateleira").value = linhaSelecionadaEditar.cells[5].textContent.trim();
        
        // Configurar data atual
        const hoje = new Date();
        const dataHoje = hoje.toISOString().split('T')[0];
        document.getElementById("livro-data").value = dataHoje;
        
        // Extrair o status do span
        const statusSpan = linhaSelecionadaEditar.cells[7].querySelector('.status-badge');
        const statusAtual = statusSpan ? statusSpan.textContent.trim() : 'Ativo';
        document.getElementById("livro-status").value = statusAtual;

        // Simular clique no link do livro para obter dados adicionais
        const linkLivro = linhaSelecionadaEditar.querySelector('a');
        if (linkLivro) {
          linkLivro.click(); // Isso vai abrir o modal de detalhes
          
          // Esperar um momento para o modal carregar
          setTimeout(() => {
            // Pegar dados do modal de detalhes
            const modalDetalhes = document.getElementById('modal-body');
            if (modalDetalhes) {
              const sinopse = modalDetalhes.querySelector('p') ? modalDetalhes.querySelector('p').textContent : '';
              const listaInfo = modalDetalhes.querySelectorAll('ul li');
              const imagem = modalDetalhes.querySelector('img') ? modalDetalhes.querySelector('img').src : '';
              
              // Preencher campos adicionais
              document.getElementById("livro-sinopse").value = sinopse;
              
              listaInfo.forEach(item => {
                const texto = item.textContent;
                if (texto.includes('Editora:')) {
                  document.getElementById("livro-editora").value = texto.split('Editora:')[1].trim();
                } else if (texto.includes('Lançamento:')) {
                  const dataLancamento = texto.split('Lançamento:')[1].trim();
                  const [dia, mes, ano] = dataLancamento.split('/');
                  document.getElementById("livro-lancamento").value = `${ano}-${mes.padStart(2, '0')}-${dia.padStart(2, '0')}`;
                }
              });

              // Preencher imagem
              const previewImagem = document.getElementById("livro-imagem-preview");
              if (previewImagem && imagem) {
                previewImagem.src = imagem;
              }

              // Fechar modal de detalhes e abrir modal de adicionar
              document.getElementById('modal').style.display = 'none';
              modalAdicionar.style.display = "block";
            }
          }, 100);
        }
      }
    });
  });

  // Evento de submit do formulário de edição
  formEditar.addEventListener("submit", function(e) {
    e.preventDefault();
    
    if (!linhaSelecionadaEditar) return;

    const inputs = this.querySelectorAll('input[required], textarea[required]');
    let todosPreenchidos = true;

    inputs.forEach(input => {
      if (!input.value.trim()) {
        todosPreenchidos = false;
      }
    });

    if (!todosPreenchidos) {
      Swal.fire({
        title: 'Erro!',
        text: 'Por favor, preencha todos os campos obrigatórios.',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#f44336'
      });
      return;
    }

    // Coletar todos os valores
    const livroNome = document.getElementById("editar-livro-nome").value;
    const livroGenero = document.getElementById("editar-livro-genero").value;
    const livroAutor = document.getElementById("editar-livro-autor").value;
    const livroQuantidade = document.getElementById("editar-livro-quantidade").value;
    const livroPrateleira = document.getElementById("editar-livro-prateleira").value;
    const livroStatus = document.getElementById("editar-livro-status").value;
    const livroEditora = document.getElementById("editar-livro-editora").value;
    const livroSinopse = document.getElementById("editar-livro-sinopse").value;
    const livroId = document.getElementById("editar-livro-id").value;

    // Formatar datas
    const dataRaw = document.getElementById("editar-livro-data").value;
    const [ano, mes, dia] = dataRaw.split("-");
    const dataFormatada = `${dia}/${mes}/${ano}`;

    const lancamentoRaw = document.getElementById("editar-livro-lancamento").value;
    const [anoLanc, mesLanc, diaLanc] = lancamentoRaw.split("-");
    const lancamentoFormatado = `${diaLanc}/${mesLanc}/${anoLanc}`;

    // Processar imagem
    const previewImagem = document.getElementById("imagem-preview-editar");
    const imagemSrc = previewImagem.src;

    // Criar conteúdo atualizado do modal
    const conteudoEditadoLivro = `
      <img src="${imagemSrc}" title="Livro" class="hmlivro" />
      <h3 style="margin-top: 0;">${livroNome}</h3>
      <p>${livroSinopse}</p><br><br><br>
      <ul>
        <li><strong>Autor:</strong> ${livroAutor}</li>
        <li><strong>Gêneros:</strong> ${livroGenero}</li>
        <li><strong>Editora:</strong> ${livroEditora}</li>
        <li><strong>Lançamento:</strong> ${lancamentoFormatado}</li>
      </ul>
      <div class="botao">
        <button type="button" class="btn ${livroStatus === 'Ativo' ? 'desativar' : 'ativar'}" onclick="alterarStatus('${livroId}')">${livroStatus === 'Ativo' ? 'DESATIVAR' : 'ATIVAR'}</button>
      </div>
    `;

    // Se estiver editando, atualizar a linha existente
    if (linhaSelecionadaEditar) {
      linhaSelecionadaEditar.innerHTML = `
        <td>${livroId}</td>
        <td><a href="#" class="detalhes-livro-novo">${livroNome}</a></td>
        <td>${livroGenero}</td>
        <td>${livroAutor}</td>
        <td>${livroQuantidade}</td>
        <td>${livroPrateleira}</td>
        <td>${dataFormatada}</td>
        <td><span class="status-badge ${livroStatus.toLowerCase()}">${livroStatus}</span></td>
      `;

      // Adicionar evento de clique no link atualizado
      const novoLink = linhaSelecionadaEditar.querySelector('.detalhes-livro-novo');
      if (novoLink) {
        novoLink.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModal(conteudoEditadoLivro);
        });
      }

      linhaSelecionadaEditar = null; // Resetar a referência
    } else {
      // Se não estiver editando, adicionar nova linha
      const tabela = document.querySelector("#livros-table tbody");
      const novaLinha = tabela.insertRow();
      novaLinha.innerHTML = `
        <td>${livroId}</td>
        <td><a href="#" class="detalhes-livro-novo">${livroNome}</a></td>
        <td>${livroGenero}</td>
        <td>${livroAutor}</td>
        <td>${livroQuantidade}</td>
        <td>${livroPrateleira}</td>
        <td>${dataFormatada}</td>
        <td><span class="status-badge ${livroStatus.toLowerCase()}">${livroStatus}</span></td>
      `;

      // Adicionar evento de clique no novo link
      const novoLink = novaLinha.querySelector('.detalhes-livro-novo');
      if (novoLink) {
        novoLink.addEventListener('click', function(e) {
          e.preventDefault();
          abrirModal(conteudoEditadoLivro);
        });
      }
    }

    // Mostrar mensagem de sucesso
    Swal.fire({
      title: 'Sucesso!',
      text: 'Livro editado com sucesso!',
      icon: 'success',
      confirmButtonText: 'OK',
      confirmButtonColor: '#4CAF50'
    }).then(() => {
      modalEditar.style.display = "none";
      formEditar.reset();
      if (document.getElementById("imagem-preview-editar")) {
        document.getElementById("imagem-preview-editar").src = "";
      }
    });
  });

  // Botão cancelar editar
  cancelarEditar.addEventListener("click", () => {
    Swal.fire({
      title: 'Cancelado!',
      text: 'Edição cancelada.',
      icon: 'error',
      confirmButtonText: 'OK',
      confirmButtonColor: '#ff9800'
    }).then(() => {
      formEditar.reset();
      modalEditar.style.display = "none";
      if (document.getElementById("imagem-preview-editar")) {
        document.getElementById("imagem-preview-editar").src = "";
      }
      
      // Adicionar e remover classe para animação
      cancelarEditar.classList.add('cancelado');
      setTimeout(() => {
        cancelarEditar.classList.remove('cancelado');
      }, 500);
    });
  });

  // Preview da imagem
  const inputImagem = document.getElementById("editar-livro-imagem");
  const previewImagem = document.getElementById("imagem-preview-editar");

  if (inputImagem && previewImagem) {
    inputImagem.addEventListener("change", function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImagem.src = e.target.result;
          previewImagem.style.display = 'block';
          previewImagem.style.maxWidth = '200px';
          previewImagem.style.maxHeight = '200px';
          previewImagem.style.marginTop = '10px';
        };
        reader.readAsDataURL(file);
      } else {
        previewImagem.src = '';
        previewImagem.style.display = 'none';
      }
    });
  }

  // Busca na tabela
  const searchInput = document.getElementById('search-input');
  if (searchInput) {
    searchInput.addEventListener('keyup', function () {
      const searchValue = this.value.toLowerCase();
      const rows = document.querySelectorAll('#livros-table tbody tr');
      
      rows.forEach(row => {
        const livroNome = row.cells[1].textContent.toLowerCase();
        const livroId = row.cells[0].textContent.trim();
        const livroAutor = row.cells[3].textContent.toLowerCase();
        const livroGenero = row.cells[2].textContent.toLowerCase();
        
        // Verifica se o texto buscado está presente em qualquer um dos campos
        const matchesSearch = livroNome.includes(searchValue) || 
                            livroId.toLowerCase().includes(searchValue) ||
                            livroAutor.includes(searchValue) ||
                            livroGenero.includes(searchValue);
        
        // Mostra ou esconde a linha baseado no resultado da busca
        row.style.display = matchesSearch ? '' : 'none';
      });
    });
  }

  // Define data mínima para o input data como a data atual
  const hoje = new Date();
  const anoAtual = hoje.getFullYear();
  const mesAtual = String(hoje.getMonth() + 1).padStart(2, '0');
  const diaAtual = String(hoje.getDate()).padStart(2, '0');
  const dataHoje = `${anoAtual}-${mesAtual}-${diaAtual}`;

  // Configurar data atual no formulário de adicionar
  const inputData = document.getElementById("livro-data");
  if (inputData) {
    inputData.value = dataHoje;
    inputData.readOnly = true;
  }

  // Configurar data atual no formulário de editar
  const inputDataEditar = document.getElementById("editar-livro-data");
  if (inputDataEditar) {
    inputDataEditar.value = dataHoje;
    inputDataEditar.readOnly = true;
  }

  // Atualizar o HTML para mostrar a data atual
  document.addEventListener('DOMContentLoaded', function() {
    // Para o formulário de adicionar
    const inputData = document.getElementById("livro-data");
    if (inputData) {
      inputData.value = dataHoje;
      inputData.readOnly = true;
    }

    // Para o formulário de editar
    const inputDataEditar = document.getElementById("editar-livro-data");
    if (inputDataEditar) {
      inputDataEditar.value = dataHoje;
      inputDataEditar.readOnly = true;
    }
  });

  // Atualizar a data quando o modal de editar é aberto
  function abrirModalEditar(codigo) {
    const funcionarioEncontrado = funcionario.find(f => f.codigo === codigo);
    if (funcionarioEncontrado) {
      // ... código existente ...
      document.getElementById('editar-livro-data').value = dataHoje;
      // ... código existente ...
    }
  }
});

// Preview de imagem para adicionar
const inputImagemAdicionar = document.getElementById("livro-imagem");
const previewImagemAdicionar = document.getElementById("livro-imagem-preview");

if (inputImagemAdicionar && previewImagemAdicionar) {
  inputImagemAdicionar.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImagemAdicionar.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
}

// Preview de imagem para editar
const inputImagemEditar = document.getElementById("livro-imagem-editar");
const previewImagemEditar = document.getElementById("imagem-preview-editar");

if (inputImagemEditar && previewImagemEditar) {
  inputImagemEditar.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImagemEditar.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
}

// Função para alterar o status do livro
function alterarStatus(livroId) {
  const linhasTabela = document.querySelectorAll("#livros-table tbody tr");
  let linhaLivro = null;

  linhasTabela.forEach((linha) => {
    const idCelula = linha.querySelector("td").textContent.trim();
    if (idCelula === livroId) {
      linhaLivro = linha;
    }
  });

  if (linhaLivro) {
    const statusAtual = linhaLivro.cells[7].textContent.trim();
    const novoStatus = statusAtual === 'Ativo' ? 'Desativado' : 'Ativo';
    const nomeLivro = linhaLivro.cells[1].textContent.trim();

    Swal.fire({
      title: 'Confirmação',
      text: `Tem certeza que deseja ${statusAtual === 'Ativo' ? 'desativar' : 'ativar'} este livro?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sim, tenho certeza',
      cancelButtonText: 'Cancelar',
      confirmButtonColor: statusAtual === 'Ativo' ? '#f44336' : '#4CAF50',
      cancelButtonColor: statusAtual === 'Ativo' ? '#4CAF50' : '#f44336'
    }).then((result) => {
      if (result.isConfirmed) {
        // Atualizar o status na tabela
        linhaLivro.cells[7].innerHTML = `<span class="status-badge ${novoStatus.toLowerCase()}">${novoStatus}</span>`;

        // Atualizar o botão no modal se estiver aberto
        const modalBody = document.getElementById('modal-body');
        if (modalBody) {
          const botaoStatus = modalBody.querySelector('.btn.desativar, .btn.ativar');
          if (botaoStatus) {
            botaoStatus.textContent = novoStatus === 'Ativo' ? 'DESATIVAR' : 'ATIVAR';
            botaoStatus.className = `btn ${novoStatus === 'Ativo' ? 'desativar' : 'ativar'}`;
            botaoStatus.onclick = () => alterarStatus(livroId);
          }
        }

        Swal.fire({
          title: 'Sucesso!',
          text: `Status do livro "${nomeLivro}" alterado para "${novoStatus}"!`,
          icon: 'success',
          confirmButtonText: 'OK',
          confirmButtonColor: '#4CAF50'
        });
      }
    });
  }
}
