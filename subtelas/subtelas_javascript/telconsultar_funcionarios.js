const modal = document.getElementById('modal');
const modalBody = document.getElementById('modal-body');
const closeModal = document.getElementById('close-modal');
const modalEditar = document.getElementById('modal-editar');
const formEditar = document.getElementById('form-editar');
const closeEditar = document.querySelector('#modal-editar .close');
const cancelarEdicao = document.getElementById('cancelar-edicao');

const funcionario = [
  {
    selector: '.detalhes-funcionarios',
    codigo: '0001',
    cpf:'153.204.489-17',
    escolaridade:'Ensino Superior Completo',
    estado:'São Paulo',
    civil:'Casado',
    cidade:'Campinas',
    bairro:'Taquaral',
    rua:'Rua José Villare',
    numero:'145',
    sexo:'Masculino',
    telefone:'(19) 99745-2210',
    nome: 'Silvio Luis de Sousa',
    cargo: 'Gerente',
    nascimento: '21/03/1965',
    efetivacao: '08/07/2024',
    imagem: 'subtelas_img/eterno_silvio.webp',
    status: 'Ativo'
  },
  {
    selector: '.detalhes-funcionarios2',
    codigo: '0002',
    cpf:'230.871.112-04',
    escolaridade:'Ensino Médio Completo',
    estado:'Rio de Janeiro',
    civil:'Divorciada',
    cidade:'Niterói',
    bairro:' Icaraí',
    rua:' Rua Álvares de Azevedo',
    numero:'88',
    sexo:'Feminino',
    telefone:'(21) 98711-3390',
    nome: 'Maria da Graça Xuxa Meneghel',
    cargo: 'Recreador', 
    nascimento: '27/03/1963',
    efetivacao: '12/12/2024',
    imagem: 'subtelas_img/xuxa_nova.jpg',
      status: 'Ativo'
  },
  {
    selector: '.detalhes-funcionarios3',
    codigo: '0003',
    cpf:'401.562.870-39',
    escolaridade:'Ensino Médio Incompleto',
    estado:'Santa Catarina',
    civil:'Solteiro',
    cidade:'Joinville',
    bairro:'América',
    rua:'Rua Expedicionário Holz',
    numero:'201',
    sexo:'Masculino',
    telefone:'(47) 99821-0543',
    nome: 'Ruan de Mello Vieira',
    cargo: 'Bibliotecário',
    nascimento: '03/07/2007',
    efetivacao: '09/06/2025',
    imagem: 'subtelas_img/paratodososgarotosquejaamei.jpg',
      status: 'Desativado'
  },
  {
    selector: '.detalhes-funcionarios4',
    codigo: '0004',
    cpf:'503.777.165-08',
    escolaridade:'Ensino Fundamental Completo',
    estado:'Bahia',
    civil:' Solteiro',
    cidade:'Salvador',
    bairro:'Itapuã',
    rua:'Rua Genipabu',
    numero:'76',
    sexo:'Masculino',
    telefone:'(71) 98144-6732',
    nome: 'Marcos Paulo Fernandes',
    cargo: 'Repositor',
    nascimento: '31/08/2008',
    efetivacao: '16/06/2025',
    imagem: 'subtelas_img/marcos_pdiddy.jpg',
    status: 'Ativo'
  },
  {
    selector: '.detalhes-funcionarios5',
    codigo: '0005',
    cpf:'082.119.794-22',
    escolaridade:'Ensino Superior Completo',
    estado:'Minas Gerais',
    civil:'Casado',
    cidade:'Belo Horizonte',
    bairro:'Savassi',
    rua:'Rua Pernambuco',
    numero:'309',
    sexo:'Masculino',
    telefone:'(31) 99700-7855',
    nome: 'Gerard Arthur Way',
    cargo: 'Recreador',
    nascimento: '09/04/1977',
    efetivacao: '05/02/2025',
    imagem: 'subtelas_img/gerard_way.jpg',
    status: 'Desativado'
  },
  {
    selector: '.detalhes-funcionarios6',
    codigo: '0006',
    cpf:'104.163.459-56',
    escolaridade:'Ensino Superior Completo',
    estado:'Paraná',
    civil:'Solteiro',
    cidade:'Magé',
    bairro:'Santa Candida',
    rua:'Albino Kolbach',
    numero:'196',
    sexo:'Masculino',
    telefone:'(21) 98861-4670',
    nome: 'Kim Sunoo',
    cargo: 'Repositor',
    nascimento: '24/06/2003',
    efetivacao: '08/10/2024',
    imagem: 'subtelas_img/sunoo.jpg',
    status: 'Desativado'
  },
  {
    selector: '.detalhes-funcionarios7',
    codigo: '0007',
    cpf:'123.456.789-10',
    escolaridade:' Ensino Superior Completo ',
    estado:'Rio de Janeiro',
    civil:' Casado',
    cidade:'Curitiba',
    bairro:'Costa e Silva',
    rua:'Bernardo Welter',
    numero:'51',
    sexo:'Masculino',
    telefone:'21) 98861-4670',
    nome: 'Dwayne Douglas Johnson',
    cargo: 'Recreador',
    nascimento: '02/05/1972',
    efetivacao: '29/09/2024',
    imagem: 'subtelas_img/Dwayne_Johnson.jpg',
    status: 'Ativo'
  },
  {
    selector: '.detalhes-funcionarios8',
    codigo: '0008',
    cpf:'093.861.509-93',
    escolaridade:' Ensino Médio Incompleto',
    estado:'Pernambuco',
    civil:'Solteiro',
    cidade:' Recife',
    bairro:'Boa Vista',
    rua:'Rua do Sossego',
    numero:'132',
    sexo:'Masculino',
    telefone:'(81) 98422-1108',
    nome: 'Mason Thames', 
    cargo: 'Recreador',
    nascimento: '10/07/2007',
    efetivacao: '11/03/2025',
    imagem: 'subtelas_img/mason_thames.webp',
    status: 'Ativo'
  },
  {
    selector: '.detalhes-funcionarios9',
    codigo: '0009',
    cpf:'188.612.345-77',
    escolaridade:'Ensino Superior Completo',
    estado:'Rio Grande do Sul',
    civil:'Solteiro',
    cidade:'Porto Alegre',
    bairro:'Moinhos de Vento',
    rua:'Rua Padre Chagas',
    numero:'441',
    sexo:'Masculino',
    telefone:'(51) 99874-6632',
    nome: 'Taylor Lautner',
    cargo: 'Bibliotecário',
    nascimento: '19/12/1980',
    efetivacao: '17/08/2024',
    imagem: 'subtelas_img/taylor_lautner.jpg',
    status: 'Ativo'
  },
  {
    selector: '.detalhes-funcionarios10',
    codigo: '0010',
    cpf:'321.997.008-63',
    escolaridade:'Ensino Superior Completo',
    estado:' São Paulo',
    civil:'Casado',
    cidade:'Santo André',
    bairro:'Jardim',
    rua:'Rua das Palmeiras',
    numero:'321',
    sexo:'Masculino',
    telefone:'(11) 98671-9932',
    nome: 'James Hetfield',
    cargo: 'Gestor',
    nascimento: '03/08/1963',
    efetivacao: '21/08/2024',
    imagem: 'subtelas_img/james_hetfield.jpg',
    status: 'Desativado'
  }
];

funcionario.forEach(funcionario => {
  const elemento = document.querySelector(funcionario.selector);
  if (elemento) {
    elemento.addEventListener('click', function (e) {
      e.preventDefault();
      // Buscar dados atualizados do localStorage
      const dadosSalvos = localStorage.getItem('funcionarios');
      if (dadosSalvos) {
        const funcionariosSalvos = JSON.parse(dadosSalvos);
        const funcionarioAtualizado = funcionariosSalvos.find(f => f.codigo === funcionario.codigo);
        if (funcionarioAtualizado) {
          funcionario = funcionarioAtualizado;
        }
      }
      
      modalBody.innerHTML = `
  <h3 class="modal-title">Ficha de ${funcionario.nome}</h3>
  
  <div class="modal-content-container">
    <div class="photo-info-container">
  <img src="${funcionario.imagem}" title="funcionário" class="hmfuncionario" />
      <div class="info-grid">
        <p class="info-item"><strong>CPF:</strong> ${funcionario.cpf}</p> 
        <p class="info-item"><strong>Sexo:</strong> ${funcionario.sexo}</p> 
        <p class="info-item"><strong>Estado Civil:</strong> ${funcionario.civil}</p> 
        <p class="info-item"><strong>Cargo:</strong> ${funcionario.cargo}</p> 
        <p class="info-item"><strong>Data de Efetivação:</strong> ${funcionario.efetivacao}</p> 
        <p class="info-item"><strong>Data de Nascimento:</strong> ${funcionario.nascimento}</p>
        <p class="info-item"><strong>Estado:</strong> ${funcionario.estado}</p> 
        <p class="info-item"><strong>Cidade:</strong> ${funcionario.cidade}</p> 
        <p class="info-item"><strong>Bairro:</strong> ${funcionario.bairro}</p> 
        <p class="info-item"><strong>Rua:</strong> ${funcionario.rua}</p> 
        <p class="info-item"><strong>Número:</strong> ${funcionario.numero}</p> 
        <p class="info-item"><strong>Telefone:</strong> ${funcionario.telefone}</p>
        <p class="info-item"><strong>Status:</strong> <span class="status-badge ${funcionario.status ? funcionario.status.toLowerCase() : 'ativo'}">${funcionario.status || 'Ativo'}</span></p>
      </div>
    </div>
  </div>

      <div class="botao">
          <button type="button" class="btn renovar" onclick="abrirModalEditar('${funcionario.codigo}')">EDITAR</button>
          ${funcionario.status === 'Desativado' ? 
            `<button type="button" class="btn ativar" onclick="alterarStatus('${funcionario.codigo}')">ATIVAR</button>` :
            `<button type="button" class="btn desativar" onclick="alterarStatus('${funcionario.codigo}')">DESATIVAR</button>`
          }
        </div>
`;

   modal.style.display = 'block';
    });
  }
});

// Função para abrir o modal de edição
function abrirModalEditar(codigo) {
    const funcionarioEncontrado = funcionario.find(f => f.codigo === codigo);
    if (funcionarioEncontrado) {
        // Preencher o formulário com os dados do funcionário
        document.getElementById('funcionario-codigo').value = funcionarioEncontrado.codigo;
        document.getElementById('funcionario-nome').value = funcionarioEncontrado.nome;
        document.getElementById('funcionario-cpf').value = funcionarioEncontrado.cpf;
        document.getElementById('funcionario-sexo').value = funcionarioEncontrado.sexo;
        document.getElementById('funcionario-civil').value = funcionarioEncontrado.civil;
        document.getElementById('funcionario-cargo').value = funcionarioEncontrado.cargo;
        document.getElementById('funcionario-nascimento').value = formatarDataISO(funcionarioEncontrado.nascimento);
        document.getElementById('funcionario-efetivacao').value = formatarDataISO(funcionarioEncontrado.efetivacao);
        document.getElementById('funcionario-estado').value = funcionarioEncontrado.estado;
        document.getElementById('funcionario-cidade').value = funcionarioEncontrado.cidade;
        document.getElementById('funcionario-bairro').value = funcionarioEncontrado.bairro;
        document.getElementById('funcionario-rua').value = funcionarioEncontrado.rua;
        document.getElementById('funcionario-numero').value = funcionarioEncontrado.numero;
        document.getElementById('funcionario-telefone').value = funcionarioEncontrado.telefone;

        // Mostrar o modal de edição
        modalEditar.style.display = 'block';
    }
}

// Função para formatar data do formato BR para ISO
function formatarDataISO(dataBR) {
    const [dia, mes, ano] = dataBR.split('/');
    return `${ano}-${mes}-${dia}`;
}

// Função para formatar data do formato ISO para BR
function formatarDataBR(dataISO) {
    const data = new Date(dataISO);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    return `${dia}/${mes}/${ano}`;
}

// Event Listeners para o modal de edição
closeEditar.addEventListener('click', () => {
    modalEditar.style.display = 'none';
});

cancelarEdicao.addEventListener('click', () => {
    modalEditar.style.display = 'none';
});

// Função para mostrar a ficha do funcionário
function mostrarFicha(e) {
    e.preventDefault();
    console.log('Mostrar ficha chamado');
    
    // Pegar o código do funcionário da linha da tabela
    const linha = this.closest('tr');
    const codigo = linha.cells[0].textContent.trim();
    console.log('Código do funcionário:', codigo);
    
    // Encontrar o funcionário
    const funcionarioAtual = funcionario.find(f => f.codigo === codigo);
    console.log('Funcionário encontrado:', funcionarioAtual);
    
    if (funcionarioAtual) {
        // Criar o conteúdo do modal
        const conteudo = `
            <h3 class="modal-title">Ficha de ${funcionarioAtual.nome}</h3>
            
            <div class="modal-content-container">
                <div class="photo-info-container">
                    <img src="${funcionarioAtual.imagem}" title="funcionário" class="hmfuncionario" />
                    <div class="info-grid">
                        <p class="info-item"><strong>CPF:</strong> ${funcionarioAtual.cpf}</p> 
                        <p class="info-item"><strong>Sexo:</strong> ${funcionarioAtual.sexo}</p> 
                        <p class="info-item"><strong>Estado Civil:</strong> ${funcionarioAtual.civil}</p> 
                        <p class="info-item"><strong>Cargo:</strong> ${funcionarioAtual.cargo}</p> 
                        <p class="info-item"><strong>Data de Efetivação:</strong> ${funcionarioAtual.efetivacao}</p> 
                        <p class="info-item"><strong>Data de Nascimento:</strong> ${funcionarioAtual.nascimento}</p>
                        <p class="info-item"><strong>Estado:</strong> ${funcionarioAtual.estado}</p> 
                        <p class="info-item"><strong>Cidade:</strong> ${funcionarioAtual.cidade}</p> 
                        <p class="info-item"><strong>Bairro:</strong> ${funcionarioAtual.bairro}</p> 
                        <p class="info-item"><strong>Rua:</strong> ${funcionarioAtual.rua}</p> 
                        <p class="info-item"><strong>Número:</strong> ${funcionarioAtual.numero}</p> 
                        <p class="info-item"><strong>Telefone:</strong> ${funcionarioAtual.telefone}</p>
                        <p class="info-item"><strong>Status:</strong> <span class="status-badge ${funcionarioAtual.status.toLowerCase()}">${funcionarioAtual.status}</span></p>
                    </div>
                </div>
            </div>

            <div class="botao">
                <button type="button" class="btn renovar" onclick="abrirModalEditar('${funcionarioAtual.codigo}')">EDITAR</button>
                <button type="button" class="btn ${funcionarioAtual.status === 'Ativo' ? 'desativar' : 'ativar'}" onclick="alterarStatus('${funcionarioAtual.codigo}')">${funcionarioAtual.status === 'Ativo' ? 'DESATIVAR' : 'ATIVAR'}</button>
            </div>
        `;
        
        // Atualizar o conteúdo do modal
        modalBody.innerHTML = conteudo;
        
        // Mostrar o modal
        modal.style.display = 'block';
        console.log('Modal exibido');
    }
}

// Função para alterar o status do funcionário
function alterarStatus(codigo) {
    const funcionarioEncontrado = funcionario.find(f => f.codigo === codigo);
    if (funcionarioEncontrado) {
        const novoStatus = funcionarioEncontrado.status === 'Ativo' ? 'Desativado' : 'Ativo';
        
        Swal.fire({
            title: 'Confirmação',
            text: `Tem certeza que deseja ${novoStatus === 'Desativado' ? 'desativar' : 'ativar'} este funcionário?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#4CAF50',
            cancelButtonColor: '#f44336'
        }).then((result) => {
            if (result.isConfirmed) {
                // Atualizar o status do funcionário
                funcionarioEncontrado.status = novoStatus;
                
                // Atualizar o status na tabela
                const linhas = document.querySelectorAll('#funcionarios-table tbody tr');
                linhas.forEach(linha => {
                    const codigoCell = linha.querySelector('td:first-child');
                    if (codigoCell && codigoCell.textContent.trim() === codigo) {
                        const statusCell = linha.querySelector('td:last-child');
                        if (statusCell) {
                            statusCell.innerHTML = `<span class="status-badge ${novoStatus.toLowerCase()}">${novoStatus}</span>`;
                        }
                    }
                });
                
                // Salvar no localStorage
                localStorage.setItem('funcionarios', JSON.stringify(funcionario));
                
                // Fechar o modal atual
                modal.style.display = 'none';
                
                // Mostrar mensagem de sucesso
                Swal.fire({
                    title: 'Sucesso!',
                    text: `${novoStatus === 'Desativado' ? 'Desativação' : 'Ativação'} feita com sucesso!`,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#4CAF50'
                });
            }
        });
    }
}

// Adicionar evento de clique ao botão salvar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado');
    
    // Carregar dados do localStorage
    const dadosSalvos = localStorage.getItem('funcionarios');
    if (dadosSalvos) {
        const funcionariosSalvos = JSON.parse(dadosSalvos);
        funcionario.length = 0;
        funcionario.push(...funcionariosSalvos);
    }
    
    // Configurar botão salvar
    const btnSalvar = document.getElementById('btn-salvar');
    if (btnSalvar) {
        btnSalvar.addEventListener('click', function(e) {
            e.preventDefault();
            salvarAlteracoes();
        });
    }

    // Adicionar event listeners aos links
    const links = document.querySelectorAll('#funcionarios-table tbody tr td a');
    links.forEach(link => {
        link.addEventListener('click', mostrarFicha);
        console.log('Event listener adicionado para:', link.textContent);
    });
    
    // Configurar botão de fechar do modal
    const closeModal = document.getElementById('close-modal');
    if (closeModal) {
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
            console.log('Modal fechado pelo botão X');
        });
    }
    
    // Fechar modal ao clicar fora dele
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            console.log('Modal fechado ao clicar fora');
        }
    });

    // Adicionar event listener para o botão de status quando o DOM estiver carregado
    console.log('DOM carregado, configurando botões de status...');
    
    // Função para configurar os botões de status
    function configurarBotoesStatus() {
        const botoesStatus = document.querySelectorAll('.btn.desativar, .btn.ativar');
        botoesStatus.forEach(botao => {
            botao.addEventListener('click', function(e) {
                e.preventDefault();
                const codigo = this.getAttribute('onclick').match(/'([^']+)'/)[1];
                console.log('Botão de status clicado para o código:', codigo);
                alterarStatus(codigo);
            });
        });
    }

    // Configurar botões iniciais
    configurarBotoesStatus();

    // Observar mudanças no DOM para configurar novos botões
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                configurarBotoesStatus();
            }
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// Função para salvar as alterações
function salvarAlteracoes() {
    console.log('Iniciando salvamento...');
    
    const codigo = document.getElementById('funcionario-codigo').value;
    console.log('Código do funcionário:', codigo);
    
    const funcionarioEncontrado = funcionario.find(f => f.codigo === codigo);
    console.log('Funcionário encontrado:', funcionarioEncontrado);
    
    if (funcionarioEncontrado) {
        // Atualizar os dados do funcionário
        const novosDados = {
            nome: document.getElementById('funcionario-nome').value,
            cpf: document.getElementById('funcionario-cpf').value,
            sexo: document.getElementById('funcionario-sexo').value,
            civil: document.getElementById('funcionario-civil').value,
            cargo: document.getElementById('funcionario-cargo').value,
            nascimento: formatarDataBR(document.getElementById('funcionario-nascimento').value),
            efetivacao: formatarDataBR(document.getElementById('funcionario-efetivacao').value),
            estado: document.getElementById('funcionario-estado').value,
            cidade: document.getElementById('funcionario-cidade').value,
            bairro: document.getElementById('funcionario-bairro').value,
            rua: document.getElementById('funcionario-rua').value,
            numero: document.getElementById('funcionario-numero').value,
            telefone: document.getElementById('funcionario-telefone').value
        };
        
        console.log('Novos dados a serem salvos:', novosDados);

        // Atualizar os dados do funcionário
        Object.assign(funcionarioEncontrado, novosDados);
        console.log('Dados atualizados no objeto:', funcionarioEncontrado);

        // Salvar no localStorage
        localStorage.setItem('funcionarios', JSON.stringify(funcionario));

        // Atualizar a tabela
        function salvarAlteracoes() {
            console.log('Iniciando salvamento...');
            
            const codigo = document.getElementById('funcionario-codigo').value;
            const index = funcionario.findIndex(f => f.codigo === codigo);
            
            if (index !== -1) {
                funcionario[index] = {
                    ...funcionario[index], // mantém propriedades como imagem
                    codigo,
                    nome: document.getElementById('funcionario-nome').value,
                    cpf: document.getElementById('funcionario-cpf').value,
                    sexo: document.getElementById('funcionario-sexo').value,
                    civil: document.getElementById('funcionario-civil').value,
                    cargo: document.getElementById('funcionario-cargo').value,
                    nascimento: formatarDataBR(document.getElementById('funcionario-nascimento').value),
                    efetivacao: formatarDataBR(document.getElementById('funcionario-efetivacao').value),
                    estado: document.getElementById('funcionario-estado').value,
                    cidade: document.getElementById('funcionario-cidade').value,
                    bairro: document.getElementById('funcionario-bairro').value,
                    rua: document.getElementById('funcionario-rua').value,
                    numero: document.getElementById('funcionario-numero').value,
                    telefone: document.getElementById('funcionario-telefone').value
                };
        
                // Salva no localStorage
                localStorage.setItem('funcionarios', JSON.stringify(funcionario));
        
                // Fecha o modal de edição
                modalEditar.style.display = 'none';
        
                // Atualiza a ficha se estiver aberta
                mostrarFichaPorCodigo(codigo);
        
                console.log('Funcionário atualizado e salvo.');
            } else {
                console.warn('Funcionário não encontrado para salvar.');
            }
        }
        
        
        // Fechar o modal de edição
        modalEditar.style.display = 'none';
        console.log('Modal de edição fechado');

        // Mostrar mensagem de sucesso
        Swal.fire({
            title: 'Sucesso!',
            text: 'Alteração feita com sucesso!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ffbcfc',
            allowOutsideClick: false,
            allowEscapeKey: false,
            customClass: {
                popup: 'swal2-popup',
                title: 'swal2-title',
                confirmButton: 'swal2-confirm'
            }
        }).then(() => {
            console.log('Atualizando ficha...');
            // Atualizar a ficha se estiver aberta
            const elemento = document.querySelector(funcionarioEncontrado.selector);
            if (elemento) {
                elemento.click();
            }
        });
    } else {
        console.log('Funcionário não encontrado com o código:', codigo);
        // Mostrar mensagem de erro
        Swal.fire({
            title: 'Erro!',
            text: 'Não foi possível encontrar o funcionário.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
}

// CAMPO DE BUSCA
document.getElementById('search-input').addEventListener('input', function () {
    const filtro = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#funcionarios-table tbody tr');

    linhas.forEach((linha) => {
        const nome = linha.querySelector('td:nth-child(2)').innerText.toLowerCase();
        if (nome.includes(filtro)) {
            linha.style.display = '';
        } else {
            linha.style.display = 'none';
        }
    });
});

// Adicionar estilos CSS para o status
const style = document.createElement('style');
style.textContent = `
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    .status-badge.ativo {
        background-color: #4CAF50;
        color: white;
    }
    .status-badge.desativado {
        background-color: #f44336;
        color: white;
    }
    .btn.desativar {
        background-color: #f44336;
    }
    .btn.ativar {
        background-color: #4CAF50;
    }
`;
document.head.appendChild(style);
