// Função para alternar o status do funcionário
function alternarStatus(idFuncionario, elemento) {
    const statusAtual = elemento.getAttribute('data-status');
    const novoStatus = statusAtual === 'ativo' ? 'inativo' : 'ativo';
    
    // Mostrar confirmação antes de alterar
    Swal.fire({
        title: 'Confirmar Alteração',
        text: `Deseja alterar o status do funcionário ${idFuncionario} de "${statusAtual === 'ativo' ? 'Ativo' : 'Inativo'}" para "${novoStatus === 'ativo' ? 'Ativo' : 'Inativo'}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Sim, alterar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Atualizar o elemento visual
            elemento.setAttribute('data-status', novoStatus);
            elemento.textContent = novoStatus === 'ativo' ? 'Ativo' : 'Inativo';
            
            // Remover classes antigas
            elemento.classList.remove('status-ativo', 'status-inativo');
            
            // Adicionar nova classe
            elemento.classList.add(`status-${novoStatus}`);
            
            // Mostrar mensagem de sucesso
            Swal.fire({
                title: 'Status Alterado!',
                text: `O funcionário ${idFuncionario} agora está ${novoStatus === 'ativo' ? 'Ativo' : 'Inativo'}`,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
            
            // Aqui você pode adicionar uma chamada AJAX para salvar no banco de dados
            // salvarStatusNoBanco(idFuncionario, novoStatus);
        }
    });
}

