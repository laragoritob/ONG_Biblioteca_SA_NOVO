document.addEventListener('DOMContentLoaded', function() {
    // Função para verificar e marcar datas em atraso
    function verificarAtrasos() {
        const rows = document.querySelectorAll('#livros-table tbody tr');
        const hoje = new Date();
        hoje.setHours(0, 0, 0, 0);
        
        rows.forEach(row => {
            const dataTexto = row.cells[4].textContent.trim();
            const [dia, mes, ano] = dataTexto.split('/');
            const data = new Date(`${ano}-${mes}-${dia}`);
            data.setHours(0, 0, 0, 0);
            
            if (data < hoje) {
                row.cells[4].classList.add('atrasado');
            } else {
                row.cells[4].classList.remove('atrasado');
            }
        });
    }

    // Verificar atrasos quando a página carregar
    verificarAtrasos();
    // Verificar atrasos a cada minuto
    setInterval(verificarAtrasos, 60000);

    // Filtro de busca na tabela
    document.getElementById('search-input').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#livros-table tbody tr');
        rows.forEach(row => {
            const livro = row.cells[1].textContent.toLowerCase();
            row.style.display = livro.includes(searchValue) ? '' : 'none';
        });
    });

    // Modal functionality
    const modal = document.getElementById('modal');
    const modalContent = document.getElementById('modal-content');
    const closeModal = document.getElementById('close-modal');
    const modalBody = document.getElementById('modal-body');

    // Close modal when clicking the X
    closeModal.onclick = function() {
        modal.style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Handle book detail clicks
    document.querySelectorAll('.detalhes-livro, .detalhes-livro2, .detalhes-livro3, .detalhes-livro4').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const row = this.closest('tr');
            const bookName = row.cells[1].textContent;
            const clientName = row.cells[2].textContent;
            const loanDate = row.cells[3].textContent;
            const returnDate = row.cells[4].textContent;
            
            modalBody.innerHTML = `
                <h2>Detalhes do Empréstimo</h2>
                <p><strong>Livro:</strong> ${bookName}</p>
                <p><strong>Cliente:</strong> ${clientName}</p>
                <p><strong>Data de Empréstimo:</strong> ${loanDate}</p>
                <p><strong>Data de Devolução:</strong> ${returnDate}</p>
            `;
            
            modal.style.display = 'block';
        });
    });
});

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
        background: #ffbcfc;
        color: rgb(0, 0, 0);
    }
    .btn.ativar {
        background: #ffbcfc;
        color: rgb(0, 0, 0);
    }
    .btn.renovar {
        background: #ffbcfc;
        color: rgb(0, 0, 0);
    }
    .confirmation-modal {
        text-align: center;
        padding: 20px;
    }
    .confirmation-modal h3 {
        margin-bottom: 15px;
        color: #333;
    }
    .confirmation-modal p {
        margin-bottom: 20px;
        font-size: 16px;
    }
    .confirmation-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .confirm-btn {
        background: #ffbcfc;
        color: rgb(0, 0, 0);
    }
    .cancel-btn {
        background: #ffbcfc;
        color: rgb(0, 0, 0);
    }
`;
document.head.appendChild(style);