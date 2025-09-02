// Função para mostrar modal de notificação
function showNotification(type, title, message) {
    const modal = document.getElementById('notificationModal');
    const icon = document.getElementById('notificationIcon');
    const iconSymbol = document.getElementById('iconSymbol');
    const titleEl = document.getElementById('notificationTitle');
    const messageEl = document.getElementById('notificationMessage');
    const btn = document.getElementById('notificationBtn');
    
    // Configurar tipo de notificação
    if (type === 'success') {
        icon.className = 'notification-icon success';
        iconSymbol.textContent = '✓';
        titleEl.textContent = title || 'Sucesso!';
        messageEl.textContent = message || 'Operação realizada com sucesso!';
    } else if (type === 'error') {
        icon.className = 'notification-icon error';
        iconSymbol.textContent = '✕';
        titleEl.textContent = title || 'Erro!';
        messageEl.textContent = message || 'Ocorreu um erro na operação!';
    } else if (type === 'warning') {
        icon.className = 'notification-icon warning';
        iconSymbol.textContent = '⚠';
        titleEl.textContent = title || 'Atenção!';
        messageEl.textContent = message || 'Atenção necessária!';
    } else if (type === 'info') {
        icon.className = 'notification-icon info';
        iconSymbol.textContent = 'ℹ';
        titleEl.textContent = title || 'Informação';
        messageEl.textContent = message || 'Informação importante!';
    }
    
    // Mostrar modal
    modal.classList.add('show');
    
    // Fechar modal ao clicar no botão
    btn.onclick = function() {
        modal.classList.remove('show');
    };
    
    // Fechar modal ao clicar no overlay
    modal.onclick = function(e) {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    };
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('show')) {
            modal.classList.remove('show');
        }
    });
}

// Função para criar o HTML do modal (caso não exista)
function createNotificationModal() {
    if (!document.getElementById('notificationModal')) {
        const modalHTML = `
            <div id="notificationModal" class="notification-modal">
                <div class="notification-content">
                    <div class="notification-header">
                        <div id="notificationIcon" class="notification-icon">
                            <span id="iconSymbol">✓</span>
                        </div>
                        <h3 id="notificationTitle" class="notification-title">Sucesso!</h3>
                        <p id="notificationMessage" class="notification-message">Operação realizada com sucesso!</p>
                    </div>
                    <div class="notification-footer">
                        <button id="notificationBtn" class="notification-btn">OK</button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHTML);
    }
}

// Inicializar modal quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    createNotificationModal();
});
