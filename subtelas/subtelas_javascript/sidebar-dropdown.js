// SIDEBAR DROPDOWN CONTROL
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar-dropdown');
    const overlay = document.getElementById('sidebar-overlay');
    const pageWrapper = document.querySelector('.page-wrapper');
    
    // Função para abrir sidebar
    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
        pageWrapper.classList.add('sidebar-open');
        sidebarToggle.classList.add('open');
    }
    
    // Função para fechar sidebar
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        pageWrapper.classList.remove('sidebar-open');
        sidebarToggle.classList.remove('open');
    }
    
    // Event listeners
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            if (sidebar.classList.contains('open')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    }
    
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
    
    // Fechar sidebar ao clicar em um item do menu
    const menuItems = document.querySelectorAll('.sidebar-menu-item');
    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Pequeno delay para permitir a navegação
            setTimeout(closeSidebar, 100);
        });
    });
    
    // Fechar sidebar com tecla ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
            closeSidebar();
        }
    });
    
    // Marcar item ativo baseado na URL atual
    const currentPage = window.location.pathname.split('/').pop();
    const activeItem = document.querySelector(`a[href*="${currentPage}"]`);
    if (activeItem) {
        activeItem.classList.add('active');
    }
});
