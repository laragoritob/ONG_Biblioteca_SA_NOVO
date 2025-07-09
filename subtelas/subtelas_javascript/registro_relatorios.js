// Função para preencher o nome do funcionário com base no ID
function preencherNomeFuncionario() {
    // Obtém o valor do ID do funcionário inserido pelo usuário
    const idFuncionario = document.getElementById('id_funcionario').value;
    
    // Simulação de um banco de dados de funcionários com ID e nome
    const funcionarios = {
        "1": "Bruno Henrique",
        "2": "Heloisa Gonçalves",
        "3": "Lara Gorito",
        "4": "Rafaela Elisa",
        "5": "Marcos Paulo",
        "6": "Ruan de Mello",
        "7": "Maria Xuxa",
        "8": "Kim Sunoo",
        "9": "George Miller",
        "10": "Dwayne Johnson",
        "11": "Taylor Lautner",
        "12": "Jake Gyllenhaal",
        "13": "James Hetfield",
        "14": "Gerard Way",
        "15": "Mason Thames",
        "16": "Gustavo Tobler"
      };      
  
    // Atribui o nome do funcionário correspondente ao ID inserido, ou uma string vazia caso não exista
    const nomeFuncionario = funcionarios[idFuncionario] || "";
    
    // Preenche o campo de nome do funcionário com o nome correspondente
    document.getElementById('nome_funcionario').value = nomeFuncionario;
}
  
// Lógica para selecionar arquivos
let arquivosSelecionados = [];

// Evento disparado quando o usuário seleciona arquivos
document.getElementById("seletor_arquivo").addEventListener("change", function () {
    const arquivos = this.files;  // Obtém os arquivos selecionados
    if (arquivos.length > 0) {
        // Exibe o box de arquivos selecionados
        document.getElementById("arquivo-box").style.display = "block";
        
        // Atualiza o título de "Arquivos selecionados"
        document.getElementById("nome-arquivo").textContent = "Arquivos selecionados:";
  
        // Limpa a lista de arquivos antes de adicionar os novos
        const listaArquivos = document.getElementById("lista-arquivos");
        listaArquivos.innerHTML = "";
  
        // Itera sobre os arquivos e os adiciona à lista
        for (let i = 0; i < arquivos.length; i++) {
            const nomeArquivo = arquivos[i].name;
  
            // Verifica se o arquivo já foi selecionado anteriormente
            if (!arquivosSelecionados.includes(nomeArquivo)) {
                // Adiciona o arquivo à lista de selecionados
                arquivosSelecionados.push(nomeArquivo);
  
                // Cria um novo item de lista e o adiciona ao DOM
                const li = document.createElement("li");
                li.textContent = nomeArquivo;
                listaArquivos.appendChild(li);
            } else {
                // Exibe um alerta caso o arquivo já tenha sido anexado
                alert(`O arquivo "${nomeArquivo}" já foi anexado.`);
            }
        }
    }
});

// Evento disparado quando o conteúdo da página é carregado
document.addEventListener("DOMContentLoaded", function() {
    // Obtém a data de hoje no formato adequado (YYYY-MM-DD)
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');  // Mes começa de 0, então soma 1
    const dia = String(hoje.getDate()).padStart(2, '0');  // Preenche com 0 se o dia for menor que 10

    const dataHoje = `${ano}-${mes}-${dia}`;  // Formata a data no padrão YYYY-MM-DD

    // Define a data de hoje como valor mínimo e máximo para o campo de data
    document.getElementById("data").setAttribute("min", dataHoje);
    document.getElementById("data").setAttribute("max", dataHoje);
});


