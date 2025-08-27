// Função para editar funcionário
function editarFuncionario(id) {
    // Redirecionar para a página de alteração
    window.location.href = 'alterar_funcionario.php?id=' + id;
}

// Função para excluir funcionário
function excluirFuncionario(id) {
    if (confirm('Tem certeza que deseja excluir este funcionário?')) {
        // Redirecionar para a página de exclusão com o ID do funcionário
        window.location.href = 'excluir_funcionario.php?id=' + id;
    }
}

// Função para filtrar a tabela de funcionários
function filtrarTabela() {
    const input = document.getElementById('search-input');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('funcionarios-table');
    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        if (found) {
            tr[i].style.display = '';
        } else {
            tr[i].style.display = 'none';
        }
    }
}
