// Controle do Sidebar Lateral
class Sidebar {
    constructor() {
        this.sidebar = document.getElementById('sidebar');
        this.overlay = document.getElementById('sidebar-overlay');
        this.toggle = document.getElementById('sidebar-toggle');
        this.pageWrapper = document.querySelector('.page-wrapper');
        this.isOpen = false;
        
        this.init();
    }
    
    init() {
        // Event listeners
        if (this.toggle) {
            this.toggle.addEventListener('click', () => this.toggleSidebar());
        }
        
        if (this.overlay) {
            this.overlay.addEventListener('click', () => this.closeSidebar());
        }
        
        // Fechar sidebar com ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen) {
                this.closeSidebar();
            }
        });
        
        // Fechar sidebar ao redimensionar para mobile
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 768 && this.isOpen) {
                this.closeSidebar();
            }
        });
        
        // Inicializar dropdowns
        this.initDropdowns();
    }
    
    toggleSidebar() {
        if (this.isOpen) {
            this.closeSidebar();
        } else {
            this.openSidebar();
        }
    }
    
    openSidebar() {
        this.isOpen = true;
        this.sidebar.classList.add('open');
        this.overlay.classList.add('active');
        this.pageWrapper.classList.add('sidebar-open');
        document.body.style.overflow = 'hidden';
        
        // Animar botão
        this.toggle.style.transform = 'rotate(180deg)';
    }
    
    closeSidebar() {
        this.isOpen = false;
        this.sidebar.classList.remove('open');
        this.overlay.classList.remove('active');
        this.pageWrapper.classList.remove('sidebar-open');
        document.body.style.overflow = '';
        
        // Reverter animação do botão
        this.toggle.style.transform = 'rotate(0deg)';
    }
    
    // Método para marcar item ativo no menu
    setActiveItem(itemId) {
        const menuItems = document.querySelectorAll('.sidebar-nav a');
        menuItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === itemId) {
                item.classList.add('active');
            }
        });
    }
    
    // Controlar dropdowns
    initDropdowns() {
        const dropdownToggles = this.sidebar.querySelectorAll('.dropdown > a');
        
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdown = toggle.parentElement;
                const isOpen = dropdown.classList.contains('open');
                
                // Fechar todos os outros dropdowns
                this.sidebar.querySelectorAll('.dropdown').forEach(d => {
                    d.classList.remove('open');
                });
                
                // Abrir/fechar o dropdown clicado
                if (!isOpen) {
                    dropdown.classList.add('open');
                }
            });
        });
    }
}

// Inicializar sidebar quando DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    new Sidebar();
    
    // Marcar item ativo baseado na URL atual
    const currentPath = window.location.pathname;
    const sidebar = new Sidebar();
    sidebar.setActiveItem(currentPath);
});

// Função para navegar para outras páginas
function navigateTo(page) {
    window.location.href = page;
}

// Função para fazer logout
function logout() {
    if (confirm('Tem certeza que deseja sair?')) {
        window.location.href = '../logout.php';
    }
}
