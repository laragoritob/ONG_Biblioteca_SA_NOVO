// ANIMAÇÃO DO BOTÃO DE CADASTRAR //
document.getElementById('form_pessoal').addEventListener('submit', function (e) {
    e.preventDefault(); // Previne o envio padrão do formulário

    const form = document.querySelector('#form_pessoal');
    const inputs = form.querySelectorAll('input');

    let todosPreenchidos = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            todosPreenchidos = false;
        }
    });

    if (todosPreenchidos) {
        const usuarioInput = form.querySelector('input[name="usuario"]');
        const senhaInput = document.getElementById('senhaInput');
        const usuario = usuarioInput.value.trim().toLowerCase();
        const senha = senhaInput.value.trim();
        const nomesProibidos = ["gerente", "repositor", "bibliotecario", "recreador", "gestor"];

        // Validação do nome de usuário
        if (nomesProibidos.includes(usuario)) {
            Swal.fire({
                icon: 'error',
                title: 'Nome de usuário inválido',
                text: `O nome "${usuario}" não é permitido. Escolha outro.`,
                confirmButtonColor: '#ffbcfc'
            });
            return; // Interrompe o envio
        }

        // Validação da senha - deve ter 8 ou mais dígitos
        console.log('Senha digitada:', senha, 'Tamanho:', senha.length);
        if (senha.length < 8) {
            console.log('Senha muito curta, bloqueando envio');
            Swal.fire({
                icon: 'error',
                title: 'Senha inválida',
                text: 'A senha deve ter pelo menos 8 caracteres.',
                confirmButtonColor: '#ffbcfc'
            });
            return; // Interrompe o envio
        }

        // Validação da data de nascimento - deve ter pelo menos 18 anos
        const dataNascimentoInput = form.querySelector('input[name="data_nascimento"]');
        const dataNascimento = new Date(dataNascimentoInput.value);
        const hoje = new Date();
        const anoNascimento = dataNascimento.getFullYear();
        const anoAtual = hoje.getFullYear();
        
        // Verificar se nasceu há pelo menos 18 anos (considerando apenas o ano)
        if (anoAtual - anoNascimento < 18) {
            Swal.fire({
                icon: 'error',
                title: 'Data de nascimento inválida',
                text: 'O valor deve ser até 31/12/2007 ou anterior.',
                confirmButtonColor: '#ffbcfc'
            });
            return; // Interrompe o envio
        }

        Swal.fire({
            title: 'Sucesso!',
            text: 'Funcionário cadastrado com sucesso!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ffbcfc'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Envia o formulário após confirmação
            }
        });
    } else {
        Swal.fire({
            title: 'Erro!',
            text: 'Por favor, preencha todos os campos antes de cadastrar.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ffbcfc'
        });
    }
});



// LIMITE DO CALENDÁRIO (DATA DE NASCIMENTO) //
    document.addEventListener('DOMContentLoaded', function () {
        const inputData = document.getElementById('data_nascimento');
        const hoje = new Date();
        
        // Calcular data máxima (18 anos atrás - até 31/12/2007 ou anterior)
        const anoMax = hoje.getFullYear() - 18;
        
        // Calcular data mínima (120 anos atrás - permitir qualquer dia do ano)
        const anoMin = hoje.getFullYear() - 120;

        // Permitir qualquer data do ano, apenas limitando pelos anos
        inputData.max = `${anoMax}-12-31`;
        inputData.min = `${anoMin}-01-01`;
    });


// LIMITE DO CALENDÁRIO (DATA EFETIVAÇÃO) //
document.addEventListener('DOMContentLoaded', function () {
    const inputData = document.getElementById('data_efetivacao');
    const hoje = new Date();
    const dia = String(hoje.getDate()).padStart(2, '0');
    const mes = String(hoje.getMonth() + 1).padStart(2, '0');
    const ano = hoje.getFullYear();

    inputData.min = `${ano}-${mes}-${dia}`;
    inputData.max = `${ano}-${mes}-${dia}`;
});


// LIMITAR O NÚMERO DE DÍGITOS DO CPF E PERMITIR APENAS NÚMEROS //
    function formatCPF(input) {
        let value = input.value.replace(/\D/g, ''); // Remove tudo que não for dígito
        value = value.slice(0, 11); // Limita a 11 dígitos
    
        // Aplica a máscara: 123.456.789-00
        if (value.length > 9) {
            input.value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
        } else if (value.length > 6) {
            input.value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, "$1.$2.$3");
        } else if (value.length > 3) {
            input.value = value.replace(/(\d{3})(\d{1,3})/, "$1.$2");
        } else {
            input.value = value;
        }
    }


// LIMITAR O NÚMERO DE DÍGITOS DO TELEFONE E PERMITIR APENAS NÚMEROS //
    function formatTelefone(input) {
        let value = input.value.replace(/\D/g, ''); // Remove tudo que não for dígito
        value = value.slice(0, 11); // Limita a 11 dígitos

        if (value.length <= 10) {
            // Telefone fixo: (xx) xxxx-xxxx
            if (value.length > 6) {
                input.value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length > 2) {
                input.value = value.replace(/(\d{2})(\d{0,4})/, '($1) $2');
            } else {
                input.value = value;
            }
        } else {
            // Celular: (xx) xxxxx-xxxx
            input.value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        }
    }


// LIMITAR O NÚMERO DE DÍGITOS DO CEP E PERMITIR APENAS NÚMEROS //
    function formatCEP(input) {
        let value = input.value.replace(/\D/g, ''); // Remove tudo que não for dígito
        value = value.slice(0, 9); // Limita a 9 dígitos
    
        // Aplica a máscara: 123.456.789-00
        if (value.length > 5) {
            input.value = value.replace(/(\d{5})(\d{0,3})/, "$1-$2");
        } else {
            input.value = value;
        }
    }


// BUSCAR PELO CEP //
    function buscarCEP(cep) {
        cep = cep.replace(/\D/g, '');
        if (cep.length !== 8) {
            Swal.fire("CEP inválido", "Digite os 8 dígitos do CEP corretamente.", "warning");
            return;
        }

        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(res => {
                if (!res.ok) throw new Error("Erro ao buscar o CEP");
                return res.json();
            })
            .then(data => {
                if (data.erro) {
                    Swal.fire("CEP não encontrado", "Verifique o número do CEP informado.", "error");
                    return;
                }

                document.querySelector('input[name="uf"]').value = data.uf || '';
                document.querySelector('input[name="cidade"]').value = data.localidade || '';
                document.querySelector('input[name="bairro"]').value = data.bairro || '';
                document.querySelector('input[name="rua"]').value = data.logradouro || '';
            })
            .catch(error => {
                Swal.fire("Erro", "Não foi possível buscar o CEP. Tente novamente.", "error");
                console.error("Erro ao buscar o CEP:", error);
            });
    }


// ATUALIZAR O NOME DO ARQUIVO AO SELECIONAR UM ARQUIVO //
    function atualizarNomeArquivo() {
        const inputArquivo = document.getElementById('foto');
        const nomeArquivo = document.getElementById('seletor_arquivo');
        const arquivosSelecionados = inputArquivo.files;
  
        // Verifica se há arquivos selecionados
        if (arquivosSelecionados.length > 0) {
          // Exibe o nome do primeiro arquivo selecionado
          nomeArquivo.value = arquivosSelecionados[0].name;
          console.log('Arquivo selecionado:', arquivosSelecionados[0].name);
        } else {
          // Caso nenhum arquivo seja selecionado
          nomeArquivo.value = 'Nenhum arquivo selecionado';
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


// VALIDAÇÃO EM TEMPO REAL DO CAMPO SENHA //
    document.addEventListener('DOMContentLoaded', function () {
        const senhaInput = document.getElementById('senhaInput');
        
        if (senhaInput) {
            // Criar elemento de feedback se não existir
            let feedbackElement = document.getElementById('senha-feedback');
            if (!feedbackElement) {
                feedbackElement = document.createElement('div');
                feedbackElement.id = 'senha-feedback';
                feedbackElement.style.fontSize = '12px';
                feedbackElement.style.marginTop = '5px';
                feedbackElement.style.fontWeight = 'bold';
                senhaInput.parentNode.insertBefore(feedbackElement, senhaInput.nextSibling);
            }
            
            senhaInput.addEventListener('input', function () {
                const senha = this.value.trim();
                
                if (senha.length === 0) {
                    feedbackElement.textContent = '';
                    feedbackElement.className = '';
                } else if (senha.length < 8) {
                    feedbackElement.textContent = `Senha muito curta (${senha.length}/8 caracteres)`;
                    feedbackElement.className = 'senha-invalida';
                } else {
                    feedbackElement.textContent = 'Senha válida ✓';
                    feedbackElement.className = 'senha-valida';
                }
            });
        }
    });


// VALIDAÇÃO EM TEMPO REAL DA DATA DE NASCIMENTO //
    document.addEventListener('DOMContentLoaded', function () {
        const dataNascimentoInput = document.getElementById('data_nascimento');
        
        if (dataNascimentoInput) {
            // Criar elemento de feedback se não existir
            let feedbackElement = document.getElementById('data-nascimento-feedback');
            if (!feedbackElement) {
                feedbackElement = document.createElement('div');
                feedbackElement.id = 'data-nascimento-feedback';
                feedbackElement.style.fontSize = '12px';
                feedbackElement.style.marginTop = '5px';
                feedbackElement.style.fontWeight = 'bold';
                dataNascimentoInput.parentNode.insertBefore(feedbackElement, dataNascimentoInput.nextSibling);
            }
            
            dataNascimentoInput.addEventListener('change', function () {
                const dataNascimento = new Date(this.value);
                const hoje = new Date();
                
                if (this.value === '') {
                    feedbackElement.textContent = '';
                    feedbackElement.className = '';
                    return;
                }
                
                // Verificar se a data é válida
                if (isNaN(dataNascimento.getTime())) {
                    feedbackElement.textContent = 'Data inválida';
                    feedbackElement.className = 'senha-invalida';
                    return;
                }
            });
        }
    });
  