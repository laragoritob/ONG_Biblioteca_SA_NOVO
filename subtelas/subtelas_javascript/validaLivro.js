// LIMITE DO CALENDÁRIO (DATA DE NASCIMENTO) //
    document.addEventListener('DOMContentLoaded', function () {
        const inputData = document.getElementById('dataNascimento');
        const hoje = new Date();
        const dia = String(hoje.getDate()).padStart(2, '0');
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const ano = hoje.getFullYear();

        inputData.max = `${ano}-${mes}-${dia}`;
    });


// LIMITE DO CALENDÁRIO (DATA DE REGISTRO) //
document.addEventListener('DOMContentLoaded', function () {
    const inputData = document.getElementById('data_registro');
    const hoje = new Date();
    const dia = String(hoje.getDate()).padStart(2, '0');
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const ano = hoje.getFullYear();
    const dataHoje = `${ano}-${mes}-${dia}`;

    inputData.min = dataHoje;
    inputData.max = dataHoje;
    inputData.value = dataHoje; // Define automaticamente a data atual
});


// ATUALIZAR O NOME DO ARQUIVO AO SELECIONAR UM ARQUIVO //
    function atualizarNomeArquivo() {
        const inputArquivo = document.getElementById('foto');
        const nomeArquivo = document.getElementById('seletor_arquivo');
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
      };


// PERMITIR APENAS LETRAS NO CAMPO DE NOME, CIDADE, ESTADO, BAIRRO E RUA //
    function permitirApenasLetras(input) {
        input.value = input.value.replace(/[^a-zA-ZÀ-ÿ\s]/g, ''); // Letras + acentos + espaços
    };

    document.addEventListener('DOMContentLoaded', function () {
        const camposLetras = ['nome', 'cidade', 'estado', 'bairro', 'rua'];
    
        camposLetras.forEach(nome => {
            const campo = document.querySelector(`input[name="${nome}"]`);
            if (campo) {
                campo.addEventListener('input', function () {
                    permitirApenasLetras(this);
                });
            }
        });
    });

// VALIDAÇÃO DO FORMULÁRIO DE LIVRO //
function validaFormulario() {
    const form = document.getElementById('form_pessoal');
    const dataRegistroInput = document.getElementById('data_registro');
    const autorInput = document.getElementById('autor');
    const editoraInput = document.getElementById('nome_editora');
    const codAutorInput = document.getElementById('cod_autor');
    const codEditoraInput = document.getElementById('cod_editora');
    const hoje = new Date();
    const dia = String(hoje.getDate()).padStart(2, '0');
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const ano = hoje.getFullYear();
    const dataHoje = `${ano}-${mes}-${dia}`;
    
    // Verificar se a data de registro é diferente do dia atual
    if (dataRegistroInput.value !== dataHoje) {
        Swal.fire({
            icon: 'error',
            title: 'Data de registro inválida',
            text: 'A data de registro deve ser o dia atual.',
            confirmButtonColor: '#6366f1'
        });
        return false; // Impede o envio do formulário
    }
    
    // Verificar se o autor foi selecionado corretamente
    if (!codAutorInput.value || codAutorInput.value === '') {
        Swal.fire({
            icon: 'error',
            title: 'Autor inválido',
            text: 'Por favor, selecione um autor válido da lista de sugestões.',
            confirmButtonColor: '#6366f1'
        });
        autorInput.focus();
        return false;
    }
    
    // Verificar se a editora foi selecionada corretamente
    if (!codEditoraInput.value || codEditoraInput.value === '') {
        Swal.fire({
            icon: 'error',
            title: 'Editora inválida',
            text: 'Por favor, selecione uma editora válida da lista de sugestões.',
            confirmButtonColor: '#6366f1'
        });
        editoraInput.focus();
        return false;
    }
    
    // Verificar se o doador foi selecionado corretamente
    const doadorInput = document.getElementById('nome_doador');
    const codDoadorInput = document.getElementById('cod_doador');
    if (!codDoadorInput.value || codDoadorInput.value === '') {
        Swal.fire({
            icon: 'error',
            title: 'Doador inválido',
            text: 'Por favor, selecione um doador válido da lista de sugestões.',
            confirmButtonColor: '#6366f1'
        });
        doadorInput.focus();
        return false;
    }
    
    return true; // Permite o envio do formulário
}

// AUTCOMPLETE PARA AUTOR //
document.addEventListener('DOMContentLoaded', function() {
    const autorInput = document.getElementById('autor');
    const autorSuggestions = document.getElementById('autor-suggestions');
    const codAutorInput = document.getElementById('cod_autor');
    let currentAutorData = null;
    let selectedIndex = -1;

    if (autorInput && autorSuggestions) {
        autorInput.addEventListener('input', function() {
            const termo = this.value.trim();
            if (termo.length >= 2) {
                buscarAutores(termo);
            } else {
                ocultarSugestoes();
            }
        });

        autorInput.addEventListener('keydown', function(e) {
            const sugestoes = autorSuggestions.querySelectorAll('.autocomplete-suggestion');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, sugestoes.length - 1);
                atualizarSelecao(sugestoes);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                atualizarSelecao(sugestoes);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && sugestoes[selectedIndex]) {
                    selecionarAutor(sugestoes[selectedIndex]);
                }
            } else if (e.key === 'Escape') {
                ocultarSugestoes();
            }
        });

        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!autorInput.contains(e.target) && !autorSuggestions.contains(e.target)) {
                ocultarSugestoes();
            }
        });
    }

    function buscarAutores(termo) {
        fetch(`buscar_autores.php?termo=${encodeURIComponent(termo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro ao buscar autores:', data.error);
                    return;
                }
                mostrarSugestoes(data, 'autor');
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    function mostrarSugestoes(dados, tipo) {
        const container = tipo === 'autor' ? autorSuggestions : document.getElementById('editora-suggestions');
        container.innerHTML = '';
        
        if (dados.length === 0) {
            container.style.display = 'none';
            return;
        }

        dados.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'autocomplete-suggestion';
            div.textContent = item.nome;
            div.dataset.codigo = item.cod_autor || item.cod_editora;
            div.dataset.nome = item.nome;
            
            div.addEventListener('click', function() {
                if (tipo === 'autor') {
                    selecionarAutor(this);
                } else {
                    selecionarEditora(this);
                }
            });
            
            container.appendChild(div);
        });
        
        container.style.display = 'block';
        selectedIndex = -1;
    }

    function selecionarAutor(elemento) {
        const nome = elemento.dataset.nome;
        const codigo = elemento.dataset.codigo;
        
        autorInput.value = nome;
        codAutorInput.value = codigo;
        currentAutorData = { nome, codigo };
        
        ocultarSugestoes();
    }

    function atualizarSelecao(sugestoes) {
        sugestoes.forEach((sugestao, index) => {
            sugestao.classList.toggle('highlighted', index === selectedIndex);
        });
    }

    function ocultarSugestoes() {
        autorSuggestions.style.display = 'none';
        selectedIndex = -1;
    }
});

// AUTCOMPLETE PARA EDITORA //
document.addEventListener('DOMContentLoaded', function() {
    const editoraInput = document.getElementById('nome_editora');
    const editoraSuggestions = document.getElementById('editora-suggestions');
    const codEditoraInput = document.getElementById('cod_editora');
    let currentEditoraData = null;
    let selectedIndex = -1;

    if (editoraInput && editoraSuggestions) {
        editoraInput.addEventListener('input', function() {
            const termo = this.value.trim();
            if (termo.length >= 2) {
                buscarEditoras(termo);
            } else {
                ocultarSugestoes();
            }
        });

        editoraInput.addEventListener('keydown', function(e) {
            const sugestoes = editoraSuggestions.querySelectorAll('.autocomplete-suggestion');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, sugestoes.length - 1);
                atualizarSelecao(sugestoes);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                atualizarSelecao(sugestoes);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && sugestoes[selectedIndex]) {
                    selecionarEditora(sugestoes[selectedIndex]);
                }
            } else if (e.key === 'Escape') {
                ocultarSugestoes();
            }
        });

        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!editoraInput.contains(e.target) && !editoraSuggestions.contains(e.target)) {
                ocultarSugestoes();
            }
        });
    }

    function buscarEditoras(termo) {
        fetch(`buscar_editoras.php?termo=${encodeURIComponent(termo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro ao buscar editoras:', data.error);
                    return;
                }
                mostrarSugestoes(data, 'editora');
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    function mostrarSugestoes(dados, tipo) {
        const container = tipo === 'editora' ? editoraSuggestions : document.getElementById('autor-suggestions');
        container.innerHTML = '';
        
        if (dados.length === 0) {
            container.style.display = 'none';
            return;
        }

        dados.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'autocomplete-suggestion';
            div.textContent = item.nome;
            div.dataset.codigo = item.cod_editora || item.cod_autor;
            div.dataset.nome = item.nome;
            
            div.addEventListener('click', function() {
                if (tipo === 'editora') {
                    selecionarEditora(this);
                } else {
                    selecionarAutor(this);
                }
            });
            
            container.appendChild(div);
        });
        
        container.style.display = 'block';
        selectedIndex = -1;
    }

    function selecionarEditora(elemento) {
        const nome = elemento.dataset.nome;
        const codigo = elemento.dataset.codigo;
        
        editoraInput.value = nome;
        codEditoraInput.value = codigo;
        currentEditoraData = { nome, codigo };
        
        ocultarSugestoes();
    }

    function atualizarSelecao(sugestoes) {
        sugestoes.forEach((sugestao, index) => {
            sugestao.classList.toggle('highlighted', index === selectedIndex);
        });
    }

    function ocultarSugestoes() {
        editoraSuggestions.style.display = 'none';
        selectedIndex = -1;
    }
});

// AUTCOMPLETE PARA DOADOR //
document.addEventListener('DOMContentLoaded', function() {
    const doadorInput = document.getElementById('nome_doador');
    const doadorSuggestions = document.getElementById('doador-suggestions');
    const codDoadorInput = document.getElementById('cod_doador');
    let currentDoadorData = null;
    let selectedIndex = -1;

    if (doadorInput && doadorSuggestions) {
        doadorInput.addEventListener('input', function() {
            const termo = this.value.trim();
            if (termo.length >= 2) {
                buscarDoadores(termo);
            } else {
                ocultarSugestoes();
            }
        });

        doadorInput.addEventListener('keydown', function(e) {
            const sugestoes = doadorSuggestions.querySelectorAll('.autocomplete-suggestion');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, sugestoes.length - 1);
                atualizarSelecao(sugestoes);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                atualizarSelecao(sugestoes);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && sugestoes[selectedIndex]) {
                    selecionarDoador(sugestoes[selectedIndex]);
                }
            } else if (e.key === 'Escape') {
                ocultarSugestoes();
            }
        });

        // Fechar sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!doadorInput.contains(e.target) && !doadorSuggestions.contains(e.target)) {
                ocultarSugestoes();
            }
        });
    }

    function buscarDoadores(termo) {
        fetch(`buscar_doadores.php?termo=${encodeURIComponent(termo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Erro ao buscar doadores:', data.error);
                    return;
                }
                mostrarSugestoes(data, 'doador');
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
    }

    function mostrarSugestoes(dados, tipo) {
        const container = tipo === 'doador' ? doadorSuggestions : document.getElementById('autor-suggestions');
        container.innerHTML = '';
        
        if (dados.length === 0) {
            container.style.display = 'none';
            return;
        }

        dados.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'autocomplete-suggestion';
            div.textContent = item.nome;
            div.dataset.codigo = item.cod_doador || item.cod_autor || item.cod_editora;
            div.dataset.nome = item.nome;
            
            div.addEventListener('click', function() {
                if (tipo === 'doador') {
                    selecionarDoador(this);
                } else if (tipo === 'autor') {
                    selecionarAutor(this);
                } else {
                    selecionarEditora(this);
                }
            });
            
            container.appendChild(div);
        });
        
        container.style.display = 'block';
        selectedIndex = -1;
    }

    function selecionarDoador(elemento) {
        const nome = elemento.dataset.nome;
        const codigo = elemento.dataset.codigo;
        
        doadorInput.value = nome;
        codDoadorInput.value = codigo;
        currentDoadorData = { nome, codigo };
        
        ocultarSugestoes();
    }

    function atualizarSelecao(sugestoes) {
        sugestoes.forEach((sugestao, index) => {
            sugestao.classList.toggle('highlighted', index === selectedIndex);
        });
    }

    function ocultarSugestoes() {
        doadorSuggestions.style.display = 'none';
        selectedIndex = -1;
    }
});

// BOTÃO PARA MOSTRAR SENHA //
    document.getElementById('mostrarSenha').addEventListener('change', function () {
        const senhaInput = document.getElementById('senhaInput');
        senhaInput.type = this.checked ? 'text' : 'password';
    });
  