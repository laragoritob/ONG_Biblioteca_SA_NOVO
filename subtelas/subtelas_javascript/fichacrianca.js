// ========== MODAL E ELEMENTOS ==========
const modal = document.getElementById('modal');
const modalBody = document.getElementById('modal-body');
const closeModal = document.getElementById('close-modal');

// Ficha do Gustavo
const fichacrianca = `
<div class="ficha-crianca">
  <img src="subtelas_img/gustavo.jpg" title="Gustavo T." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Gustavo T.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 16/09/2017</p>
    <p><strong>Responsável:</strong> Mirian Back Tobler</p>
    <p><strong>Telefone:</strong> (47)9 8888-9999</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> Florianópolis</p>
    <p><strong>Bairro:</strong> Centro</p>
    <p><strong>Rua:</strong> Rua dos Açores</p>
    <p><strong>Número:</strong> 45</p>
    <h4><em>Gustavo é um menino alegre e muito comunicativo, adora contar histórias e fazer amigos por onde passa.
    Seu passatempo favorito é desenhar e colorir, além de construir castelos de areia quando está na praia.
    É bastante criativo e sempre tem uma nova ideia para compartilhar.
    Gosta de passar tempo com a mãe, com quem lê livros antes de dormir.
    É gentil e carismático, sempre disposto a ajudar os colegas.</em></h4>
   
  </div>
</div>
`;

// Ficha da Helena
const fichacrianca2 = `
<div class="ficha-crianca">
  <img src="subtelas_img/helena.jpg" title="Helena L." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Helena L.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 29/06/2019</p>
    <p><strong>Responsável:</strong> Mariana Lopes</p>
    <p><strong>Telefone:</strong> (47)9 7456-2389</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> Blumenau</p>
    <p><strong>Bairro:</strong> Itoupava Central</p>
    <p><strong>Rua:</strong> Rua Hermann Weege</p>
    <p><strong>Número:</strong> 107</p>
    <h4><em>Helena é uma menina doce e encantadora, sempre com um sorriso no rosto.
    Ela ama brincar com seus brinquedos de animais e sonha em ser veterinária quando crescer.
    Adora músicas infantis e já decora letras com facilidade.
    Gosta de estar com a família e se diverte em passeios ao ar livre.
    É muito carinhosa e conquista todos com seu jeitinho meigo.</em></h4>
   
  </div>
</div>
`;

// Ficha da Emanuela
const fichacrianca3 = `
<div class="ficha-crianca">
  <img src="subtelas_img/emanuela.WEBP" title="Emanuela W." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Emanuela W.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 07/11/2020</p>
    <p><strong>Responsável:</strong> João W.</p>
    <p><strong>Telefone:</strong> (47)9 6666-3322</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> Itajaí</p>
    <p><strong>Bairro:</strong> São João</p>
    <p><strong>Rua:</strong> Rua São Paulo</p>
    <p><strong>Número:</strong> 36</p>
    <h4><em>Emanuela é uma garotinha cheia de energia e muito criativa.
    Gosta de brincar de casinha, cuidar das bonecas e imitar adultos com muito humor.
    Ela adora vestir fantasias e encenar pequenas histórias para os familiares.
    Seu laço com o pai é muito forte, com quem divide momentos de brincadeira e carinho.
    Tem um espírito alegre e contagia a todos com sua presença.</em></h4>
   
  </div>
</div>
`;

// Ficha do João
const fichacrianca4 = `
<div class="ficha-crianca">
  <img src="subtelas_img/joao.jpg" title="João A." class="foto-crianca" />
  <div class="info-crianca">
    <h3>João A.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 28/02/2018</p>
    <p><strong>Responsável:</strong> Luana Atanazio</p>
    <p><strong>Telefone:</strong> (47)9 9988-7766</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> Joinville</p>
    <p><strong>Bairro:</strong> Costa e Silva</p>
    <p><strong>Rua:</strong> Affonso Zastrow</p>
    <p><strong>Número:</strong> 12</p>
    <h4><em>João é uma criança muito observadora e com uma mente criativa.
    Ele adora construir e montar coisas com blocos, além de ser fascinado por novos desafios.
    Sempre buscando aprender algo novo, ele é extremamente dedicado nas atividades escolares.
    João gosta de explorar a natureza, observando plantas e insetos em seu caminho.
    É também muito afetuoso com os pais e ama passar tempo com eles, especialmente em viagens familiares.</em></h4>
   
  </div>
</div>
`;

// Ficha do Matheus
const fichacrianca5 = `
<div class="ficha-crianca">
  <img src="subtelas_img/matheus.jpeg" title="Matheus D." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Matheus D.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 19/04/2016</p>
    <p><strong>Responsável:</strong> Carlos D.</p>
    <p><strong>Telefone:</strong> (47)9 1111-4433</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> São José</p>
    <p><strong>Bairro:</strong> Barreiros</p>
    <p><strong>Rua:</strong> Rua João Pessoa</p>
    <p><strong>Número:</strong> 78</p>
    <h4><em>Matheus é uma criança tranquila e muito observadora, com grande interesse por ciências naturais.
    Ele adora assistir documentários sobre animais e explorar o mundo ao seu redor.
    Sempre em busca de novos conhecimentos, é muito aplicado nas atividades escolares.
    Tem uma imaginação fértil e cria histórias divertidas com seus brinquedos.
    Matheus é um amigo leal e sempre disposto a ajudar seus colegas em momentos de necessidade.</em></h4>
   
  </div>
</div>
`;

// Ficha do Ian
const fichacrianca6 = `
<div class="ficha-crianca">
  <img src="subtelas_img/ian.jpg" title="Ian L." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Ian L.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 10/08/2020</p>
    <p><strong>Responsável:</strong> Júlia L.</p>
    <p><strong>Telefone:</strong> (47)9 3333-2233</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> Lages</p>
    <p><strong>Bairro:</strong> Santa Mônica</p>
    <p><strong>Rua:</strong> Rua Anhangüera</p>
    <p><strong>Número:</strong> 56</p>
    <h4><em>Ian é um garoto muito curioso, com uma mente rápida e cheia de ideias criativas.
    Ele adora explorar seu ambiente e descobrir novas coisas a cada dia.
    Gosta de brincar com seus brinquedos educativos e tem uma forte atração por tecnologia.
    Ian sempre busca aprender de forma divertida e está sempre envolvido em projetos com seus amigos.
    Sua energia contagiante e entusiasmo fazem dele uma criança encantadora e cheia de vida.</em></h4>
   
  </div>
</div>
`;

// Ficha da Tatiane
const fichacrianca7 = `
<div class="ficha-crianca">
  <img src="subtelas_img/tatiane.jpg" title="Tatiane V." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Tatiane V.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 31/12/2020</p>
    <p><strong>Responsável:</strong> Renata V.</p>
    <p><strong>Telefone:</strong> (47)9 4444-5566</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> Blumenau</p>
    <p><strong>Bairro:</strong> Velha Central</p>
    <p><strong>Rua:</strong> Rua São Pedro</p>
    <p><strong>Número:</strong> 10</p>
    <h4><em>Tatiane é uma menina muito sociável e adora interagir com outras crianças.
    Ela tem uma personalidade vibrante e gosta de se expressar através da arte.
    Gosta de pintar, desenhar e criar suas próprias histórias.
    Tatiane tem um grande amor pelos animais e sempre encontra formas de cuidar deles.
    Ela é muito atenciosa com os mais velhos e está sempre disposta a ajudar quando necessário.</em></h4>
   
  </div>
</div>
`;

// Ficha do Matheus
const fichacrianca8 = `
<div class="ficha-crianca">
  <img src="subtelas_img/matheush.jpg" title="Matheus H." class="foto-crianca" />
  <div class="info-crianca">
    <h3>Matheus H.</h3>
    <p> </p>
    <p><strong>Data de Nascimento:</strong> 19/04/2016</p>
    <p><strong>Responsável:</strong> Juliano H.</p>
    <p><strong>Telefone:</strong> (47)9 1111-4433</p>
    <p><strong>Estado:</strong> Santa Catarina</p>
    <p><strong>Cidade:</strong> São José</p>
    <p><strong>Bairro:</strong> Barreiros</p>
    <p><strong>Rua:</strong> Rua João Pessoa</p>
    <p><strong>Número:</strong> 78</p>
    <h4><em>Matheus é um menino muito tranquilo, sempre sorridente e com uma curiosidade natural.
    Ele adora fazer novas amizades e é muito sociável com todos.
    Além disso, Matheus tem um grande interesse por futebol e está sempre praticando o esporte com seus amigos.
    Tem uma energia única e sempre se destaca por sua disposição em aprender coisas novas.
    Matheus é muito atencioso e tem um carinho imenso pelos seus familiares.</em></h4>
   
  </div>
</div>
`;

// ========== BOTÕES DO MODAL ==========
const botaoContainer = document.createElement('div');
botaoContainer.classList.add('botao-container');

const botaoEditar = document.createElement('button');
botaoEditar.textContent = 'Editar';
botaoEditar.classList.add('editar-ficha');
botaoContainer.appendChild(botaoEditar);

const botaoDesativar = document.createElement('button');
botaoDesativar.textContent = 'Desativar';
botaoDesativar.classList.add('desativar-ficha');
botaoContainer.appendChild(botaoDesativar);

// ========== FUNÇÕES ==========
function abrirModal(fichaHtml) {
  modalBody.innerHTML = fichaHtml;
  
  // Recreate the button container and buttons
  const botaoContainer = document.createElement('div');
  botaoContainer.classList.add('botao-container');

  const botaoEditar = document.createElement('button');
  botaoEditar.textContent = 'Editar';
  botaoEditar.classList.add('editar-ficha');
  botaoContainer.appendChild(botaoEditar);

  const botaoDesativar = document.createElement('button');
  botaoDesativar.classList.add('desativar-ficha');

  // Check if the ficha is desativada
  const nome = modalBody.querySelector('.info-crianca h3')?.textContent.trim();
  const isDesativado = Array.from(document.querySelectorAll('#Fichas-table tbody tr')).some(row => {
    return row.cells[1].textContent.trim() === nome && row.classList.contains('ficha-desativada');
  });

  if (isDesativado) {
    botaoEditar.style.opacity = '0.5';
    botaoEditar.style.cursor = 'not-allowed';
    botaoEditar.onclick = () => {
      const mensagem = document.createElement('div');
      mensagem.textContent = 'Não é possível editar uma ficha desativada';
      mensagem.style.cssText = `
        background-color: #F44336;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        margin-top: 10px;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        animation: fadeOut 3s forwards;
      `;
      document.body.appendChild(mensagem);
      setTimeout(() => mensagem.remove(), 3000);
    };

    botaoDesativar.textContent = 'Reativar';
    botaoDesativar.style.backgroundColor = '#4CAF50';
    botaoDesativar.onclick = () => reativarFicha(nome);
  } else {
    botaoEditar.style.opacity = '1';
    botaoEditar.style.cursor = 'pointer';
    botaoEditar.onclick = () => habilitarEdicao(modalBody.querySelector('.ficha-crianca'));

    botaoDesativar.textContent = 'Desativar';
    botaoDesativar.style.backgroundColor = '#F44336';
    botaoDesativar.onclick = () => desativarFicha(nome);
  }

  botaoContainer.appendChild(botaoDesativar);
  modalBody.appendChild(botaoContainer);
  modal.style.display = 'block';
}

function desativarFicha(nome) {
  if (!nome) return;

  const rows = document.querySelectorAll('#Fichas-table tbody tr');
  rows.forEach(row => {
    const nomeCell = row.cells[1].textContent.trim();
    if (nomeCell === nome) {
      // Update status in table
      const statusCell = row.cells[row.cells.length - 1];
      statusCell.innerHTML = '<span class="status-desativado">Desativado</span>';
      row.classList.add('ficha-desativada');

      // Update buttons in modal
      const botaoEditar = modalBody.querySelector('.editar-ficha');
      const botaoDesativar = modalBody.querySelector('.desativar-ficha');

      if (botaoEditar && botaoDesativar) {
        // Disable edit button
        botaoEditar.style.opacity = '0.5';
        botaoEditar.style.cursor = 'not-allowed';
        botaoEditar.onclick = () => {
          const mensagem = document.createElement('div');
          mensagem.textContent = 'Não é possível editar uma ficha desativada';
          mensagem.style.cssText = `
            background-color: #F44336;
            color: white;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            margin-top: 10px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            animation: fadeOut 3s forwards;
          `;
          document.body.appendChild(mensagem);
          setTimeout(() => mensagem.remove(), 3000);
        };

        // Change to Reativar button
        botaoDesativar.textContent = 'Reativar';
        botaoDesativar.style.backgroundColor = '#4CAF50';
        botaoDesativar.onclick = () => reativarFicha(nome);
      }
    }
  });
}

function reativarFicha(nome) {
  if (!nome) return;

  const rows = document.querySelectorAll('#Fichas-table tbody tr');
  rows.forEach(row => {
    const nomeCell = row.cells[1].textContent.trim();
    if (nomeCell === nome) {
      // Update status in table
      const statusCell = row.cells[row.cells.length - 1];
      statusCell.innerHTML = '<span class="status-ativo">Ativo</span>';
      row.classList.remove('ficha-desativada');

      // Update buttons in modal
      const botaoEditar = modalBody.querySelector('.editar-ficha');
      const botaoDesativar = modalBody.querySelector('.desativar-ficha');

      if (botaoEditar && botaoDesativar) {
        // Enable edit button
        botaoEditar.style.opacity = '1';
        botaoEditar.style.cursor = 'pointer';
        botaoEditar.onclick = () => habilitarEdicao(modalBody.querySelector('.ficha-crianca'));

        // Change back to Desativar button
        botaoDesativar.textContent = 'Desativar';
        botaoDesativar.style.backgroundColor = '#F44336';
        botaoDesativar.onclick = () => desativarFicha(nome);
      }

      // Show success message
      const mensagem = document.createElement('div');
      mensagem.textContent = 'Ficha reativada com sucesso!';
      mensagem.style.cssText = `
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        margin-top: 10px;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        animation: fadeOut 3s forwards;
      `;
      document.body.appendChild(mensagem);
      setTimeout(() => mensagem.remove(), 3000);
    }
  });
}

function habilitarEdicao(container) {
  // Store the original image before clearing the container
  const originalImage = container.querySelector('.foto-crianca');
  const originalSrc = originalImage?.src || '';
  const originalTitle = originalImage?.title || '';

  // Change button text to "Salvar" when entering edit mode
  const botaoEditar = modalBody.querySelector('.editar-ficha');
  if (botaoEditar) {
    botaoEditar.textContent = 'Salvar';
  }

  // Extract information from the current ficha
  const infoContainer = container.querySelector('.info-crianca');
  const nome = infoContainer.querySelector('h3').textContent;
  
  // Function to extract value from paragraph with given label
  const getValue = (label) => {
    const p = Array.from(infoContainer.querySelectorAll('p')).find(p => 
      p.querySelector('strong')?.textContent.includes(label)
    );
    return p ? p.textContent.replace(p.querySelector('strong').textContent, '').trim() : '';
  };

  // Get all the values
  const dataNascimento = getValue('Data de Nascimento:');
  const responsavel = getValue('Responsável:');
  const telefone = getValue('Telefone:');
  const estado = getValue('Estado:');
  const cidade = getValue('Cidade:');
  const bairro = getValue('Bairro:');
  const rua = getValue('Rua:');
  const numero = getValue('Número:');
  
  // Get the description
  const descricao = infoContainer.querySelector('h4 em')?.textContent || '';

  // Clear the container and create a new form
  container.innerHTML = `
    <div class="edit-form">
      <div class="image-preview" style="text-align: center; margin-bottom: 20px;">
        <img src="${originalSrc}" title="${originalTitle}" class="foto-crianca" style="max-width: 200px; height: auto;"/>
      </div>
      <h2>Editar Ficha</h2>
      
      <div class="form-row">
        <div class="form-group">
          <label>Nome:</label>
          <input type="text" id="nome" class="form-input" value="${nome}">
        </div>
        <div class="form-group">
          <label>Data de Nascimento:</label>
          <input type="text" id="data-nascimento" class="form-input" value="${dataNascimento}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Responsável:</label>
          <input type="text" id="responsavel" class="form-input" value="${responsavel}">
        </div>
        <div class="form-group">
          <label>Telefone:</label>
          <input type="text" id="telefone" class="form-input" value="${telefone}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Estado:</label>
          <input type="text" id="estado" class="form-input" value="${estado}">
        </div>
        <div class="form-group">
          <label>Cidade:</label>
          <input type="text" id="cidade" class="form-input" value="${cidade}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Bairro:</label>
          <input type="text" id="bairro" class="form-input" value="${bairro}">
        </div>
        <div class="form-group">
          <label>Rua:</label>
          <input type="text" id="rua" class="form-input" value="${rua}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Número:</label>
          <input type="text" id="numero" class="form-input" value="${numero}">
        </div>
      </div>

      <div class="form-row">
        <div class="form-group" style="flex: 1;">
          <label>Descrição:</label>
          <textarea id="descricao" class="form-input" style="height: 100px; resize: vertical;">${descricao}</textarea>
        </div>
      </div>
    </div>
  `;

  // Store the original information in data attributes for later use
  const editForm = container.querySelector('.edit-form');
  editForm.dataset.originalSrc = originalSrc;
  editForm.dataset.originalTitle = originalTitle;
  editForm.dataset.originalNome = nome;

  // Update the style for the edit form
  const editStyle = document.createElement('style');
  editStyle.innerHTML = `
    .edit-form {
      width: 100%;
      padding: 20px;
      background-color: #fff;
      border-radius: 8px;
    }

    .edit-form h2 {
      color: #673AB7;
      text-align: center;
      margin-bottom: 20px;
      font-size: 24px;
    }

    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 15px;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 5px;
      color: #333;
      font-weight: bold;
    }

    .form-input {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 20px;
      background-color: #E3F2FD;
      font-size: 14px;
      width: 100%;
      box-sizing: border-box;
    }

    textarea.form-input {
      border-radius: 15px;
      font-family: inherit;
      line-height: 1.5;
      padding: 12px;
    }

    select.form-input {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 1rem center;
      background-size: 1em;
      padding-right: 2.5em;
    }

    .botao-container {
      margin-top: 20px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .botao-container button {
      padding: 10px 30px;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    .editar-ficha {
      background-color: #673AB7;
      color: white;
    }

    .desativar-ficha {
      background-color: #F44336;
      color: white;
    }

    .editar-ficha:hover {
      background-color: #5E35B1;
    }

    .desativar-ficha:hover {
      opacity: 0.9;
    }

    .botao-container button:active {
      transform: translateY(0);
      box-shadow: 0 1px 4px rgba(0,0,0,0.2);
    }

    .botao-container button[disabled],
    .botao-container button.disabled {
      opacity: 0.5;
      cursor: not-allowed;
      transform: none;
      box-shadow: none;
    }
  `;
  document.head.appendChild(editStyle);

  botaoEditar.textContent = 'Salvar';
  botaoEditar.onclick = () => salvarEdicao(container);
}

function salvarEdicao(container) {
  // Get all the form values
  const nome = document.getElementById('nome').value;
  const dataNascimento = document.getElementById('data-nascimento').value;
  const responsavel = document.getElementById('responsavel').value;
  const telefone = document.getElementById('telefone').value;
  const estado = document.getElementById('estado').value;
  const cidade = document.getElementById('cidade').value;
  const bairro = document.getElementById('bairro').value;
  const rua = document.getElementById('rua').value;
  const numero = document.getElementById('numero').value;
  const descricao = document.getElementById('descricao').value;

  // Get the stored original image information
  const editForm = container.querySelector('.edit-form');
  const originalSrc = editForm.dataset.originalSrc;
  const originalTitle = editForm.dataset.originalTitle;
  const originalNome = editForm.dataset.originalNome;

  // Create the updated ficha HTML
  const fichaAtualizada = `
    <div class="ficha-crianca">
      <img src="${originalSrc}" title="${originalTitle}" class="foto-crianca" />
      <div class="info-crianca">
        <h3>${nome}</h3>
        <p> </p>
        <p><strong>Data de Nascimento:</strong> ${dataNascimento}</p>
        <p><strong>Responsável:</strong> ${responsavel}</p>
        <p><strong>Telefone:</strong> ${telefone}</p>
        <p><strong>Estado:</strong> ${estado}</p>
        <p><strong>Cidade:</strong> ${cidade}</p>
        <p><strong>Bairro:</strong> ${bairro}</p>
        <p><strong>Rua:</strong> ${rua}</p>
        <p><strong>Número:</strong> ${numero}</p>
        <h4><em>${descricao}</em></h4>
      </div>
    </div>
  `;

  // Update the table
  const rows = document.querySelectorAll('#Fichas-table tbody tr');
  rows.forEach(row => {
    const cells = row.cells;
    if (cells[1].textContent.trim() === originalNome) {
      cells[1].textContent = nome;
      if (cells[2]) cells[2].textContent = responsavel;
      if (cells[3]) cells[3].textContent = telefone;
    }
  });

  // Update the stored ficha variable
  const fichaVariables = {
    'Gustavo T.': 'fichacrianca',
    'Helena L.': 'fichacrianca2',
    'Emanuela W.': 'fichacrianca3',
    'João A.': 'fichacrianca4',
    'Matheus D.': 'fichacrianca5',
    'Ian L.': 'fichacrianca6',
    'Tatiane V.': 'fichacrianca7',
    'Matheus H.': 'fichacrianca8'
  };

  const fichaVariable = fichaVariables[originalNome];
  if (fichaVariable) {
    window[fichaVariable] = fichaAtualizada;
  }

  // Update the current modal content
  modalBody.innerHTML = fichaAtualizada;

  // Recreate the button container and buttons
  const botaoContainer = document.createElement('div');
  botaoContainer.classList.add('botao-container');

  const botaoEditar = document.createElement('button');
  botaoEditar.textContent = 'Editar';
  botaoEditar.classList.add('editar-ficha');
  botaoContainer.appendChild(botaoEditar);

  const botaoDesativar = document.createElement('button');
  botaoDesativar.classList.add('desativar-ficha');

  // Check if the ficha is desativada
  const isDesativado = Array.from(document.querySelectorAll('#Fichas-table tbody tr')).some(row => {
    return row.cells[1].textContent.trim() === nome && row.classList.contains('ficha-desativada');
  });

  if (isDesativado) {
    botaoEditar.style.opacity = '0.5';
    botaoEditar.style.cursor = 'not-allowed';
    botaoEditar.onclick = () => {
      const mensagem = document.createElement('div');
      mensagem.textContent = 'Não é possível editar uma ficha desativada';
      mensagem.style.cssText = `
        background-color: #F44336;
        color: white;
        padding: 10px;
        border-radius: 4px;
        text-align: center;
        margin-top: 10px;
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        animation: fadeOut 3s forwards;
      `;
      document.body.appendChild(mensagem);
      setTimeout(() => mensagem.remove(), 3000);
    };

    botaoDesativar.textContent = 'Reativar';
    botaoDesativar.style.backgroundColor = '#4CAF50';
    botaoDesativar.onclick = () => reativarFicha(nome);
  } else {
    botaoEditar.style.opacity = '1';
    botaoEditar.style.cursor = 'pointer';
    botaoEditar.onclick = () => habilitarEdicao(modalBody.querySelector('.ficha-crianca'));

    botaoDesativar.textContent = 'Desativar';
    botaoDesativar.style.backgroundColor = '#F44336';
    botaoDesativar.onclick = () => desativarFicha(nome);
  }

  botaoContainer.appendChild(botaoDesativar);
  modalBody.appendChild(botaoContainer);

  // Add success message
  const successMessage = document.createElement('div');
  successMessage.className = 'success-message';
  successMessage.textContent = 'Alterações salvas com sucesso!';
  successMessage.style.cssText = `
    background-color: #4CAF50;
    color: white;
    padding: 10px;
    border-radius: 4px;
    text-align: center;
    margin-top: 10px;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    animation: fadeOut 3s forwards;
  `;

  document.body.appendChild(successMessage);
  setTimeout(() => successMessage.remove(), 3000);
}

// Event Listeners for each ficha
function setupFichaListeners() {
  document.querySelectorAll('.fichacrianca').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca);
    };
  });

  document.querySelectorAll('.fichacrianca2').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca2);
    };
  });

  document.querySelectorAll('.fichacrianca3').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca3);
    };
  });

  document.querySelectorAll('.fichacrianca4').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca4);
    };
  });

  document.querySelectorAll('.fichacrianca5').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca5);
    };
  });

  document.querySelectorAll('.fichacrianca6').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca6);
    };
  });

  document.querySelectorAll('.fichacrianca7').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca7);
    };
  });

  document.querySelectorAll('.fichacrianca8').forEach(link => {
    link.onclick = (e) => {
      e.preventDefault();
      abrirModal(fichacrianca8);
    };
  });
}

// Initialize event listeners
document.addEventListener('DOMContentLoaded', () => {
  setupFichaListeners();
  initializeStatusColumn();
});

// Close modal handlers
closeModal.onclick = function() {
  modal.style.display = 'none';
  setupFichaListeners(); // Reattach listeners when modal is closed
};

window.onclick = function(event) {
  if (event.target === modal) {
    modal.style.display = 'none';
    setupFichaListeners(); // Reattach listeners when modal is closed
  }
};

document.getElementById('search-input').addEventListener('keyup', function () {
  const searchValue = this.value.toLowerCase();
  const rows = document.querySelectorAll('#Fichas-table tbody tr');
  rows.forEach(row => {
    const nome = row.cells[1].textContent.toLowerCase();
    row.style.display = nome.includes(searchValue) ? '' : 'none';
  });
});

// Initialize status column when the page loads
document.addEventListener('DOMContentLoaded', initializeStatusColumn);

// ========== ESTILO ==========
const style = document.createElement('style');
style.innerHTML = `
#modal-body {
  font-family: Arial, sans-serif;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  padding: 20px;
  width: 100%;
  box-sizing: border-box;
}

.ficha-crianca {
  display: flex;
  margin-bottom: 20px;
  width: 100%;
  justify-content: flex-start;
}

.foto-crianca {
  width: 400px;
  height: 400px;
  margin-right: 20px;
  object-fit: contain;
}

.info-crianca {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px 40px;
  justify-content: start;
}

.info-crianca h4 {
  grid-column: 1 / -1;
  margin-top: 20px;
}

.info-crianca h3 {
  margin-top: 0;
  margin-bottom: 10px;
}

.info-crianca p {
  margin: 5px 0;
}

.info-crianca p strong {
  font-weight: bold;
}

.botao-container {
  display: flex;
  gap: 10px;
  margin-top: 10px;
  justify-content: center;
  width: 100%;
}

.botao-container button {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  border: none;
  border-radius: 50px;
  transition: all 0.3s ease;
  color: white;
}

.editar-ficha {
  background-color:rgb(221, 115, 224);
}

.editar-ficha:hover {
  background-color:rgb(184, 89, 187);
}

.desativar-ficha {
  background-color:rgb(221, 115, 224);
}

.desativar-ficha:hover {
  opacity: 0.9;
}

.botao-container button:active {
  transform: translateY(0);
  box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}

.botao-container button[disabled],
.botao-container button.disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

@media screen and (max-width: 768px) {
  .ficha-crianca {
    flex-direction: column;
    align-items: center;
    text-align: center;
  }

  .info-crianca {
    align-items: center;
  }
}
`;
document.head.appendChild(style);

// Add styles for status indicators
const statusStyles = document.createElement('style');
statusStyles.innerHTML = `
  .status-ativo {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 20px;
    font-weight: bold;
    display: inline-block;
    text-align: center;
    min-width: 80px;
  }

  .status-desativado {
    background-color: #F44336;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    font-size: 20px;
    font-weight: bold;
    display: inline-block;
    text-align: center;
    min-width: 80px;
  }

  .ficha-desativada {
    opacity: 0.7;
  }

  .status-column {
    text-align: center;
    padding: 10px;
  }

  .editar-ficha {
    background-color: #673AB7;
    color: white;
  }

  .desativar-ficha {
    background-color: #F44336;
    color: white;
  }

  .desativar-ficha[data-status="ativo"] {
    background-color: #F44336;
  }

  .desativar-ficha[data-status="desativado"] {
    background-color: #4CAF50;
  }

  /* Table header styling */
  #Fichas-table thead th {
    background-color: #90CAF9 !important;
    color: #000;
    padding: 12px;
    text-align: center;
  }

  /* Table row styling */
  #Fichas-table tbody tr:nth-child(even) td {
    background-color: #E1F5FE !important;
  }

  #Fichas-table tbody tr:nth-child(odd) td {
    background-color: #B3E5FC !important;
  }

  /* Status column specific */
  .status-column {
    text-align: center;
    padding: 10px;
  }
`;
document.head.appendChild(statusStyles);

// Function to initialize status column if it doesn't exist
function initializeStatusColumn() {
  const table = document.getElementById('Fichas-table');
  if (!table) return;

  // Add header if it doesn't exist
  const headerRow = table.querySelector('thead tr');
  if (headerRow && !headerRow.querySelector('.status-header')) {
    const statusHeader = document.createElement('th');
    statusHeader.className = 'status-header';
    statusHeader.textContent = 'STATUS';
    headerRow.appendChild(statusHeader);
  }

  // Add status cells to each row if they don't exist
  const rows = table.querySelectorAll('tbody tr');
  rows.forEach(row => {
    if (!row.querySelector('.status-column')) {
      const statusCell = document.createElement('td');
      statusCell.className = 'status-column';
      const isDesativado = row.classList.contains('ficha-desativada');
      statusCell.innerHTML = `<span class="status-${isDesativado ? 'desativado' : 'ativo'}">${isDesativado ? 'Desativado' : 'Ativo'}</span>`;
      row.appendChild(statusCell);
    }
  });
}

// Add styles for table columns
const tableStyles = document.createElement('style');
tableStyles.innerHTML = `
  .id-column,
  .nome-column,
  .responsavel-column,
  .telefone-column,
  .status-column {
    padding: 12px !important;
    text-align: center !important;
    vertical-align: middle !important;
    background-color: inherit !important;
  }

  #Fichas-table tbody tr td {
    padding: 12px !important;
    text-align: center !important;
    vertical-align: middle !important;
    background-color: inherit !important;
  }

  #Fichas-table tbody tr:nth-child(even) td {
    background-color: #E1F5FE !important;
  }

  #Fichas-table tbody tr:nth-child(odd) td {
    background-color: #B3E5FC !important;
  }

  .ficha-desativada td {
    opacity: 0.7 !important;
  }

  #Fichas-table {
    border-collapse: collapse !important;
    width: 100% !important;
  }

  #Fichas-table th,
  #Fichas-table td {
    border: 1px solid #ddd !important;
  }
`;
document.head.appendChild(tableStyles);
