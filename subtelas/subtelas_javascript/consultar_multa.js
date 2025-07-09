// Filtro de busca na tabela
document.getElementById('search-input').addEventListener('keyup', function () {
    const searchValue = this.value.toLowerCase();
    const rows = document.querySelectorAll('#multas-table tbody tr');
    rows.forEach(row => {
        const multa = row.cells[1].textContent.toLowerCase();
        row.style.display = multa.includes(searchValue) ? '' : 'none';
    });
});

// Funções do Modal
function openModal() {
    document.getElementById('modal-status').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal-status').style.display = 'none';
    document.getElementById('form-status').reset();
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    const botaoMudar = document.getElementById('botao-mudar');
    const modalStatus = document.getElementById('modal-status');
    const closeBtn = document.getElementById('close-status');
    const cancelarBtn = document.getElementById('cancelar-status');
    const formStatus = document.getElementById('form-status');

    // Abrir modal ao clicar no botão mudar
    botaoMudar.addEventListener('click', function() {
        Swal.fire({
            title: 'Digite o ID da multa',
            input: 'text',
            inputLabel: 'ID da multa (ex: #0001)',
            inputPlaceholder: 'Insira o ID aqui',
            showCancelButton: true,
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#77d440',
            cancelButtonColor: '#c93c8c'
        }).then((result) => {
            if (result.isConfirmed) {
                const idDigitado = result.value.trim();
                const rows = document.querySelectorAll('#multas-table tbody tr');
                let linhaEncontrada = null;

                rows.forEach(row => {
                    if (row.cells[0].textContent === idDigitado) {
                        linhaEncontrada = row;
                    }
                });
                
                if (linhaEncontrada) {
                    window.linhaSelecionada = linhaEncontrada;
                    openModal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Multa não encontrada',
                        text: `Nenhuma multa com ID ${idDigitado} foi encontrada.`,
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#7076c9'
                    });
                }
            }
        });
    });

    // Fechar modal com o X
    closeBtn.addEventListener('click', closeModal);

    // Fechar modal com botão cancelar
    cancelarBtn.addEventListener('click', closeModal);

    // Fechar modal clicando fora
    window.addEventListener('click', function(e) {
        if (e.target === modalStatus) {
            closeModal();
        }
    });

    // Submeter o formulário
    formStatus.addEventListener('submit', function(e) {
        e.preventDefault();

        const statusSelecionado = formStatus.querySelector('input[name="novo-status"]:checked');
        if (!statusSelecionado || !window.linhaSelecionada) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Por favor, selecione um status.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#7076c9'
            });
            return;
        }

        const novoStatus = statusSelecionado.value;
        window.linhaSelecionada.cells[4].textContent = novoStatus;

        Swal.fire({
            title: 'Sucesso!',
            text: 'Status alterado com sucesso!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#7076c9'
        }).then(() => {
            closeModal();
        });
    });
});