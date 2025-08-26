// LIMITE DO CALENDÁRIO (DATA DE NASCIMENTO) //
    document.addEventListener('DOMContentLoaded', function () {
        const inputData = document.getElementById('dataNascimento');
        const hoje = new Date();
        const dia = String(hoje.getDate()).padStart(2, '0');
        const mes = String(hoje.getMonth() + 1).padStart(2, '0');
        const ano = hoje.getFullYear();

        inputData.max = `${ano}-${mes}-${dia}`;
    });


// LIMITE DO CALENDÁRIO (DATA EFETIVAÇÃO) //
document.addEventListener('DOMContentLoaded', function () {
    const inputData = document.getElementById('dataEfetivacao');
    const hoje = new Date();
    const dia = String(hoje.getDate()).padStart(2, '0');
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const ano = hoje.getFullYear();

    inputData.min = `${ano}-${mes}-${dia}`;
    inputData.max = `${ano}-${mes}-${dia}`;
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

// BOTÃO PARA MOSTRAR SENHA //
    document.getElementById('mostrarSenha').addEventListener('change', function () {
        const senhaInput = document.getElementById('senhaInput');
        senhaInput.type = this.checked ? 'text' : 'password';
    });
  