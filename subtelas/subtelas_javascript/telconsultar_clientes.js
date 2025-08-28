// Função para editar funcionário
function editarCliente(id) {
    // Redirecionar para a página de alteração
    window.location.href = 'alterar_cliente.php?id=' + id;
}

// Função para excluir funcionário
function excluirCliente(id) {
    if (confirm('Tem certeza que deseja excluir este cliente?')) {
        // Redirecionar para a página de exclusão com o ID do funcionário
        window.location.href = 'excluir_cliente.php?id=' + id;
    }
}
