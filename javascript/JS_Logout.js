document.addEventListener('DOMContentLoaded', function() {
    // Função para fazer logout
    function logout() {
        // Limpa qualquer dado de sessão se necessário
        sessionStorage.clear();
        // Redireciona para a página de login
        window.location.replace('login.html');
    }

    // Adiciona o evento de clique ao botão de logout
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    }
}); 